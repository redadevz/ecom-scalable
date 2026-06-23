<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\ItemCategory\BulkDestroyItemCategoryRequest;
use App\Http\Requests\CraftablePro\ItemCategory\CreateItemCategoryRequest;
use App\Http\Requests\CraftablePro\ItemCategory\DestroyItemCategoryRequest;
use App\Http\Requests\CraftablePro\ItemCategory\EditItemCategoryRequest;
use App\Http\Requests\CraftablePro\ItemCategory\IndexItemCategoryRequest;
use App\Http\Requests\CraftablePro\ItemCategory\StoreItemCategoryRequest;
use App\Http\Requests\CraftablePro\ItemCategory\UpdateItemCategoryRequest;
use App\Models\ItemCategory;
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

class ItemCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexItemCategoryRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $itemCategoriesQuery = QueryBuilder::for(ItemCategory::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'parent_category_id', 'name', 'description', 'is_active'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'parent_category_id', 'name', 'description', 'is_active', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($itemCategoriesQuery->select(['id'])->pluck('id'));
        }

        $itemCategories = $itemCategoriesQuery
            ->with([])
            ->select('id', 'parent_category_id', 'name', 'description', 'is_active', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('itemCategories_url', $request->fullUrl());

        return Inertia::render('ItemCategory/Index', [
            'itemCategories' => $itemCategories,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateItemCategoryRequest $request): Response
    {
        return Inertia::render('ItemCategory/Create', [
            
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreItemCategoryRequest $request): RedirectResponse
    {
        $itemCategory = ItemCategory::create($request->validated());
        
        return redirect()->route('craftable-pro.item-categories.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditItemCategoryRequest $request, ItemCategory $itemCategory): Response
    {
        
        return Inertia::render('ItemCategory/Edit', [
            'itemCategory' => $itemCategory,
            
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemCategoryRequest $request, ItemCategory $itemCategory): RedirectResponse
    {
        $itemCategory->update($request->validated());
        
        if (session('itemCategories_url')) {
            return redirect(session('itemCategories_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.item-categories.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyItemCategoryRequest $request, ItemCategory $itemCategory): RedirectResponse
    {
        
        $itemCategory->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyItemCategoryRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    ItemCategory::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                ItemCategory::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
