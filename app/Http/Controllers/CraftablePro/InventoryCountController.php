<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\InventoryCount\BulkDestroyInventoryCountRequest;
use App\Http\Requests\CraftablePro\InventoryCount\CreateInventoryCountRequest;
use App\Http\Requests\CraftablePro\InventoryCount\DestroyInventoryCountRequest;
use App\Http\Requests\CraftablePro\InventoryCount\EditInventoryCountRequest;
use App\Http\Requests\CraftablePro\InventoryCount\IndexInventoryCountRequest;
use App\Http\Requests\CraftablePro\InventoryCount\StoreInventoryCountRequest;
use App\Http\Requests\CraftablePro\InventoryCount\UpdateInventoryCountRequest;
use App\Http\Requests\CraftablePro\InventoryCountItem\IndexInventoryCountItemRequest;
use App\Models\InventoryCount;
use App\Services\InventoryCountService;
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

class InventoryCountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexInventoryCountRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $inventoryCountsQuery = QueryBuilder::for(InventoryCount::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'store_id', 'description', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'store_id', 'physical_count_time', 'change_stock_time', 'description', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($inventoryCountsQuery->select(['id'])->pluck('id'));
        }

        $inventoryCounts = $inventoryCountsQuery
            ->with([])
            ->select('id', 'store_id', 'physical_count_time', 'change_stock_time', 'description', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('inventoryCounts_url', $request->fullUrl());

        return Inertia::render('InventoryCount/Index', [
            'inventoryCounts' => $inventoryCounts,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateInventoryCountRequest $request): Response
    {
        return Inertia::render('InventoryCount/Create', [
            'stores' => \App\Models\Store::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreInventoryCountRequest $request): RedirectResponse
    {
        $inventoryCount = InventoryCount::create($request->validated());
        
        return redirect()->route('craftable-pro.inventory-counts.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditInventoryCountRequest $request, InventoryCount $inventoryCount): Response
    {
        
        return Inertia::render('InventoryCount/Edit', [
            'inventoryCount' => $inventoryCount,
            'stores' => \App\Models\Store::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInventoryCountRequest $request, InventoryCount $inventoryCount): RedirectResponse
    {
        $inventoryCount->update($request->validated());
        
        if (session('inventoryCounts_url')) {
            return redirect(session('inventoryCounts_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.inventory-counts.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyInventoryCountRequest $request, InventoryCount $inventoryCount): RedirectResponse
    {
        
        $inventoryCount->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyInventoryCountRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    InventoryCount::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                InventoryCount::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    public function apply(InventoryCount $inventoryCount, InventoryCountService $counts){
        try{
            $counts->apply($inventoryCount);
            return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
        } catch (\App\Exceptions\InventoryCountAlreadyAppliedException | \App\Exceptions\InsufficientStockException $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
}
