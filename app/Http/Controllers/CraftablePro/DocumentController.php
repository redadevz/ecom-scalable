<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\Document\BulkDestroyDocumentRequest;
use App\Http\Requests\CraftablePro\Document\CreateDocumentRequest;
use App\Http\Requests\CraftablePro\Document\DestroyDocumentRequest;
use App\Http\Requests\CraftablePro\Document\EditDocumentRequest;
use App\Http\Requests\CraftablePro\Document\IndexDocumentRequest;
use App\Http\Requests\CraftablePro\Document\StoreDocumentRequest;
use App\Http\Requests\CraftablePro\Document\UpdateDocumentRequest;
use App\Models\Document;
use Brackets\CraftablePro\Queries\Filters\FuzzyFilter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexDocumentRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $documentsQuery = QueryBuilder::for(Document::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'store_id', 'document_type_id', 'sale_order_id', 'purchase_id', 'stock_return_id', 'inventory_count_id', 'loss_and_damage_id', 'created_by', 'number', 'external_number', 'description', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'store_id', 'document_type_id', 'sale_order_id', 'purchase_id', 'stock_return_id', 'inventory_count_id', 'loss_and_damage_id', 'created_by', 'number', 'external_number', 'description', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($documentsQuery->select(['id'])->pluck('id'));
        }

        $documents = $documentsQuery
            ->with([])
            ->select('id', 'store_id', 'document_type_id', 'sale_order_id', 'purchase_id', 'stock_return_id', 'inventory_count_id', 'loss_and_damage_id', 'created_by', 'number', 'external_number', 'description', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('documents_url', $request->fullUrl());

        return Inertia::render('Document/Index', [
            'documents' => $documents,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateDocumentRequest $request): Response
    {
        return Inertia::render('Document/Create', [
            'craftable_pro_users' => \Brackets\CraftablePro\Models\CraftableProUser::orderBy('email')->get(['id', 'email']),
            'document_types' => \App\Models\DocumentType::orderBy('name')->get(['id', 'name']),
            'inventory_counts' => \App\Models\InventoryCount::orderBy('id')->get(['id', 'description'])->map(fn ($m) => ['id' => $m->id, 'description' => '#' . $m->id . ' ' . ($m->description ?? '')]),
            'loss_and_damages' => \App\Models\LossAndDamage::orderBy('id')->get(['id', 'description'])->map(fn ($m) => ['id' => $m->id, 'description' => '#' . $m->id . ' ' . ($m->description ?? '')]),
            'purchases' => \App\Models\Purchase::orderBy('id')->get(['id', 'description'])->map(fn ($m) => ['id' => $m->id, 'description' => '#' . $m->id . ' ' . ($m->description ?? '')]),
            'order_headers' => \App\Models\OrderHeader::orderBy('order_no')->get(['id', 'order_no']),
            'stock_returns' => \App\Models\StockReturn::orderBy('id')->get(['id', 'description'])->map(fn ($m) => ['id' => $m->id, 'description' => '#' . $m->id . ' ' . ($m->description ?? '')]),
            'stores' => \App\Models\Store::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreDocumentRequest $request): RedirectResponse
    {
        $document = Document::create($request->validated());
        
        return redirect()->route('craftable-pro.documents.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditDocumentRequest $request, Document $document): Response
    {
        
        return Inertia::render('Document/Edit', [
            'document' => $document,
            'craftable_pro_users' => \Brackets\CraftablePro\Models\CraftableProUser::orderBy('email')->get(['id', 'email']),
            'document_types' => \App\Models\DocumentType::orderBy('name')->get(['id', 'name']),
            'inventory_counts' => \App\Models\InventoryCount::orderBy('id')->get(['id', 'description'])->map(fn ($m) => ['id' => $m->id, 'description' => '#' . $m->id . ' ' . ($m->description ?? '')]),
            'loss_and_damages' => \App\Models\LossAndDamage::orderBy('id')->get(['id', 'description'])->map(fn ($m) => ['id' => $m->id, 'description' => '#' . $m->id . ' ' . ($m->description ?? '')]),
            'purchases' => \App\Models\Purchase::orderBy('id')->get(['id', 'description'])->map(fn ($m) => ['id' => $m->id, 'description' => '#' . $m->id . ' ' . ($m->description ?? '')]),
            'order_headers' => \App\Models\OrderHeader::orderBy('order_no')->get(['id', 'order_no']),
            'stock_returns' => \App\Models\StockReturn::orderBy('id')->get(['id', 'description'])->map(fn ($m) => ['id' => $m->id, 'description' => '#' . $m->id . ' ' . ($m->description ?? '')]),
            'stores' => \App\Models\Store::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentRequest $request, Document $document): RedirectResponse
    {
        $document->update($request->validated());
        
        if (session('documents_url')) {
            return redirect(session('documents_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.documents.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyDocumentRequest $request, Document $document): RedirectResponse
    {
        
        $document->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyDocumentRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    Document::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                Document::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
