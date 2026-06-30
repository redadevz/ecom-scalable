<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\InventoryCountItem\BulkDestroyInventoryCountItemRequest;
use App\Http\Requests\CraftablePro\InventoryCountItem\CreateInventoryCountItemRequest;
use App\Http\Requests\CraftablePro\InventoryCountItem\DestroyInventoryCountItemRequest;
use App\Http\Requests\CraftablePro\InventoryCountItem\EditInventoryCountItemRequest;
use App\Http\Requests\CraftablePro\InventoryCountItem\IndexInventoryCountItemRequest;
use App\Http\Requests\CraftablePro\InventoryCountItem\StoreInventoryCountItemRequest;
use App\Http\Requests\CraftablePro\InventoryCountItem\UpdateInventoryCountItemRequest;
use App\Models\InventoryCountItem;
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

class InventoryCountItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexInventoryCountItemRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $inventoryCountItemsQuery = QueryBuilder::for(InventoryCountItem::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'inventory_count_id', 'item_id', 'quantity_counted', 'quantity_expected', 'quantity_change', 'description', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'inventory_count_id', 'item_id', 'quantity_counted', 'quantity_expected', 'quantity_change', 'description', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($inventoryCountItemsQuery->select(['id'])->pluck('id'));
        }

        $inventoryCountItems = $inventoryCountItemsQuery
            ->with([])
            ->select('id', 'inventory_count_id', 'item_id', 'quantity_counted', 'quantity_expected', 'quantity_change', 'description', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('inventoryCountItems_url', $request->fullUrl());

        return Inertia::render('InventoryCountItem/Index', [
            'inventoryCountItems' => $inventoryCountItems,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateInventoryCountItemRequest $request): Response
    {
        return Inertia::render('InventoryCountItem/Create', [
            'inventory_counts' => \App\Models\InventoryCount::orderBy('id')->get(['id', 'description'])->map(fn ($m) => ['id' => $m->id, 'description' => '#' . $m->id . ' ' . ($m->description ?? '')]),
            'items' => \App\Models\Item::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreInventoryCountItemRequest $request): RedirectResponse
    {
        $inventoryCountItem = InventoryCountItem::create($request->validated());
        
        return redirect()->route('craftable-pro.inventory-count-items.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditInventoryCountItemRequest $request, InventoryCountItem $inventoryCountItem): Response
    {
        
        return Inertia::render('InventoryCountItem/Edit', [
            'inventoryCountItem' => $inventoryCountItem,
            'inventory_counts' => \App\Models\InventoryCount::orderBy('id')->get(['id', 'description'])->map(fn ($m) => ['id' => $m->id, 'description' => '#' . $m->id . ' ' . ($m->description ?? '')]),
            'items' => \App\Models\Item::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInventoryCountItemRequest $request, InventoryCountItem $inventoryCountItem): RedirectResponse
    {
        $inventoryCountItem->update($request->validated());
        
        if (session('inventoryCountItems_url')) {
            return redirect(session('inventoryCountItems_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.inventory-count-items.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyInventoryCountItemRequest $request, InventoryCountItem $inventoryCountItem): RedirectResponse
    {
        
        $inventoryCountItem->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyInventoryCountItemRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    InventoryCountItem::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                InventoryCountItem::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
