<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\PurchaseItem\BulkDestroyPurchaseItemRequest;
use App\Http\Requests\CraftablePro\PurchaseItem\CreatePurchaseItemRequest;
use App\Http\Requests\CraftablePro\PurchaseItem\DestroyPurchaseItemRequest;
use App\Http\Requests\CraftablePro\PurchaseItem\EditPurchaseItemRequest;
use App\Http\Requests\CraftablePro\PurchaseItem\IndexPurchaseItemRequest;
use App\Http\Requests\CraftablePro\PurchaseItem\StorePurchaseItemRequest;
use App\Http\Requests\CraftablePro\PurchaseItem\UpdatePurchaseItemRequest;
use App\Models\PurchaseItem;
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

class PurchaseItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexPurchaseItemRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $purchaseItemsQuery = QueryBuilder::for(PurchaseItem::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'purchase_id', 'item_id', 'supplier_price_before_tax', 'supplier_tax_value', 'supplier_price_after_tax', 'supplier_discount_value', 'quantity', 'return_amount', 'description', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'purchase_id', 'item_id', 'supplier_price_before_tax', 'supplier_tax_value', 'supplier_price_after_tax', 'supplier_discount_value', 'quantity', 'return_amount', 'description', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($purchaseItemsQuery->select(['id'])->pluck('id'));
        }

        $purchaseItems = $purchaseItemsQuery
            ->with([])
            ->select('id', 'purchase_id', 'item_id', 'supplier_price_before_tax', 'supplier_tax_value', 'supplier_price_after_tax', 'supplier_discount_value', 'quantity', 'return_amount', 'description', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('purchaseItems_url', $request->fullUrl());

        return Inertia::render('PurchaseItem/Index', [
            'purchaseItems' => $purchaseItems,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreatePurchaseItemRequest $request): Response
    {
        return Inertia::render('PurchaseItem/Create', [
            'items' => \App\Models\Item::orderBy('name')->get(['id', 'name']),
            'purchases' => \App\Models\Purchase::orderBy('id')->get(['id', 'description'])->map(fn ($m) => ['id' => $m->id, 'description' => '#' . $m->id . ' ' . ($m->description ?? '')]),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StorePurchaseItemRequest $request): RedirectResponse
    {
        $purchaseItem = PurchaseItem::create($request->validated());
        
        return redirect()->route('craftable-pro.purchase-items.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditPurchaseItemRequest $request, PurchaseItem $purchaseItem): Response
    {
        
        return Inertia::render('PurchaseItem/Edit', [
            'purchaseItem' => $purchaseItem,
            'items' => \App\Models\Item::orderBy('name')->get(['id', 'name']),
            'purchases' => \App\Models\Purchase::orderBy('id')->get(['id', 'description'])->map(fn ($m) => ['id' => $m->id, 'description' => '#' . $m->id . ' ' . ($m->description ?? '')]),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePurchaseItemRequest $request, PurchaseItem $purchaseItem): RedirectResponse
    {
        $purchaseItem->update($request->validated());
        
        if (session('purchaseItems_url')) {
            return redirect(session('purchaseItems_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.purchase-items.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyPurchaseItemRequest $request, PurchaseItem $purchaseItem): RedirectResponse
    {
        
        $purchaseItem->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyPurchaseItemRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    PurchaseItem::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                PurchaseItem::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
