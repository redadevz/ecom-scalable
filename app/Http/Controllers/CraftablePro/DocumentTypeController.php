<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\DocumentType\BulkDestroyDocumentTypeRequest;
use App\Http\Requests\CraftablePro\DocumentType\CreateDocumentTypeRequest;
use App\Http\Requests\CraftablePro\DocumentType\DestroyDocumentTypeRequest;
use App\Http\Requests\CraftablePro\DocumentType\EditDocumentTypeRequest;
use App\Http\Requests\CraftablePro\DocumentType\IndexDocumentTypeRequest;
use App\Http\Requests\CraftablePro\DocumentType\StoreDocumentTypeRequest;
use App\Http\Requests\CraftablePro\DocumentType\UpdateDocumentTypeRequest;
use App\Models\DocumentType;
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

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexDocumentTypeRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $documentTypesQuery = QueryBuilder::for(DocumentType::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'document_category_id', 'name', 'description', 'is_active', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'document_category_id', 'name', 'description', 'is_active', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($documentTypesQuery->select(['id'])->pluck('id'));
        }

        $documentTypes = $documentTypesQuery
            ->with(['documentCategory:id,name'])
            ->select('id', 'document_category_id', 'name', 'description', 'is_active', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('documentTypes_url', $request->fullUrl());

        return Inertia::render('DocumentType/Index', [
            'documentTypes' => $documentTypes,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateDocumentTypeRequest $request): Response
    {
        return Inertia::render('DocumentType/Create', [
            'document_categories' => \App\Models\DocumentCategory::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreDocumentTypeRequest $request): RedirectResponse
    {
        $documentType = DocumentType::create($request->validated());
        
        return redirect()->route('craftable-pro.document-types.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditDocumentTypeRequest $request, DocumentType $documentType): Response
    {
        
        return Inertia::render('DocumentType/Edit', [
            'documentType' => $documentType,
            'document_categories' => \App\Models\DocumentCategory::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentTypeRequest $request, DocumentType $documentType): RedirectResponse
    {
        $documentType->update($request->validated());
        
        if (session('documentTypes_url')) {
            return redirect(session('documentTypes_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.document-types.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyDocumentTypeRequest $request, DocumentType $documentType): RedirectResponse
    {
        
        $documentType->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyDocumentTypeRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    DocumentType::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                DocumentType::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
