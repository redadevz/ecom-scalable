<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\Item\BulkDestroyItemRequest;
use App\Http\Requests\CraftablePro\Item\CreateItemRequest;
use App\Http\Requests\CraftablePro\Item\DestroyItemRequest;
use App\Http\Requests\CraftablePro\Item\EditItemRequest;
use App\Http\Requests\CraftablePro\Item\IndexItemRequest;
use App\Http\Requests\CraftablePro\Item\StoreItemRequest;
use App\Http\Requests\CraftablePro\Item\UpdateItemRequest;
use App\Models\Item;
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

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexItemRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $itemsQuery = QueryBuilder::for(Item::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'store_id', 'item_category_id', 'supplier_id', 'measure_unit_id', 'sku_code', 'name', 'description', 'is_service', 'in_stock', 'using_default_quantity', 'default_quantity', 'current_stock_quantity', 'preferred_stock_quantity', 'min_stock_quantity', 'low_stock_warning', 'low_stock_quantity', 'is_active', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'store_id', 'item_category_id', 'supplier_id', 'measure_unit_id', 'sku_code', 'name', 'description', 'is_service', 'in_stock', 'using_default_quantity', 'default_quantity', 'current_stock_quantity', 'preferred_stock_quantity', 'min_stock_quantity', 'low_stock_warning', 'low_stock_quantity', 'is_active', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($itemsQuery->select(['id'])->pluck('id'));
        }

        $items = $itemsQuery
            ->with([
                'itemCategory:id,name',
                'prices' => fn ($q) => $q->where('is_active', true)->latest('id')->select('id', 'item_id', 'sale_price'),
                'media',
            ])
            ->select('id', 'store_id', 'item_category_id', 'supplier_id', 'measure_unit_id', 'sku_code', 'name', 'image', 'description', 'is_service', 'in_stock', 'using_default_quantity', 'default_quantity', 'current_stock_quantity', 'preferred_stock_quantity', 'min_stock_quantity', 'low_stock_warning', 'low_stock_quantity', 'is_active', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('items_url', $request->fullUrl());

        return Inertia::render('Item/Index', [
            'items' => $items,
        ]);
    }

    /**
     * Upload an item image; returns its public URL.
     */
    public function uploadImage(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'file' => ['required', 'image', 'max:5120'], // 5 MB
        ]);

        $path = $request->file('file')->store('items', 'public');

        return response()->json(['url' => \Illuminate\Support\Facades\Storage::url($path)]);
    }

    /**
     * Grid (card) view of items, with optional category filter.
     */
    public function grid(Request $request): Response
    {
        $categoryId = $request->integer('category') ?: null;
        $search = trim((string) $request->get('search'));

        $items = Item::query()
            ->with([
                'itemCategory:id,name',
                'prices' => fn ($q) => $q->where('is_active', true)->latest('id')->select('id', 'item_id', 'sale_price'),
                'media',
            ])
            ->when($categoryId, fn ($q) => $q->where('item_category_id', $categoryId))
            ->when($search !== '', fn ($q) => $q->where(fn ($w) => $w->where('name', 'like', "%{$search}%")->orWhere('sku_code', 'like', "%{$search}%")))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Item/Grid', [
            'items'      => $items,
            'categories' => ItemCategory::withCount('items')->orderBy('name')->get(['id', 'name']),
            'filters'    => ['category' => $categoryId, 'search' => $search],
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateItemRequest $request): Response
    {
        return Inertia::render('Item/Create', [
            'item_categories' => \App\Models\ItemCategory::orderBy('name')->get(['id', 'name']),
            'measure_units' => \App\Models\MeasureUnit::orderBy('name')->get(['id', 'name']),
            'stores' => \App\Models\Store::orderBy('name')->get(['id', 'name']),
            'suppliers' => \App\Models\Supplier::orderBy('code')->get(['id', 'code']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreItemRequest $request): RedirectResponse
    {
        $item = Item::create($request->validated());
        
        return redirect()->route('craftable-pro.items.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditItemRequest $request, Item $item): Response
    {
        
        return Inertia::render('Item/Edit', [
            'item' => $item,
            'item_categories' => \App\Models\ItemCategory::orderBy('name')->get(['id', 'name']),
            'measure_units' => \App\Models\MeasureUnit::orderBy('name')->get(['id', 'name']),
            'stores' => \App\Models\Store::orderBy('name')->get(['id', 'name']),
            'suppliers' => \App\Models\Supplier::orderBy('code')->get(['id', 'code']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, Item $item): RedirectResponse
    {
        $item->update($request->validated());
        
        if (session('items_url')) {
            return redirect(session('items_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.items.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyItemRequest $request, Item $item): RedirectResponse
    {
        
        $item->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyItemRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    Item::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                Item::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
