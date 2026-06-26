<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\DocumentCategory\BulkDestroyDocumentCategoryRequest;
use App\Http\Requests\CraftablePro\DocumentCategory\CreateDocumentCategoryRequest;
use App\Http\Requests\CraftablePro\DocumentCategory\DestroyDocumentCategoryRequest;
use App\Http\Requests\CraftablePro\DocumentCategory\EditDocumentCategoryRequest;
use App\Http\Requests\CraftablePro\DocumentCategory\IndexDocumentCategoryRequest;
use App\Http\Requests\CraftablePro\DocumentCategory\StoreDocumentCategoryRequest;
use App\Http\Requests\CraftablePro\DocumentCategory\UpdateDocumentCategoryRequest;
use App\Models\DocumentCategory;
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

class DocumentCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexDocumentCategoryRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $documentCategoriesQuery = QueryBuilder::for(DocumentCategory::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'name', 'description', 'is_active', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'name', 'description', 'is_active', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($documentCategoriesQuery->select(['id'])->pluck('id'));
        }

        $documentCategories = $documentCategoriesQuery
            ->with([])
            ->select('id', 'name', 'description', 'is_active', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('documentCategories_url', $request->fullUrl());

        return Inertia::render('DocumentCategory/Index', [
            'documentCategories' => $documentCategories,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateDocumentCategoryRequest $request): Response
    {
        return Inertia::render('DocumentCategory/Create', [
            
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreDocumentCategoryRequest $request): RedirectResponse
    {
        $documentCategory = DocumentCategory::create($request->validated());
        
        return redirect()->route('craftable-pro.document-categories.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditDocumentCategoryRequest $request, DocumentCategory $documentCategory): Response
    {
        
        return Inertia::render('DocumentCategory/Edit', [
            'documentCategory' => $documentCategory,
            
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentCategoryRequest $request, DocumentCategory $documentCategory): RedirectResponse
    {
        $documentCategory->update($request->validated());
        
        if (session('documentCategories_url')) {
            return redirect(session('documentCategories_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.document-categories.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyDocumentCategoryRequest $request, DocumentCategory $documentCategory): RedirectResponse
    {
        
        $documentCategory->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyDocumentCategoryRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    DocumentCategory::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                DocumentCategory::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
