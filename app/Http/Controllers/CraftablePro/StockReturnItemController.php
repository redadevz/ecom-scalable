<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\StockReturnItem\BulkDestroyStockReturnItemRequest;
use App\Http\Requests\CraftablePro\StockReturnItem\CreateStockReturnItemRequest;
use App\Http\Requests\CraftablePro\StockReturnItem\DestroyStockReturnItemRequest;
use App\Http\Requests\CraftablePro\StockReturnItem\EditStockReturnItemRequest;
use App\Http\Requests\CraftablePro\StockReturnItem\IndexStockReturnItemRequest;
use App\Http\Requests\CraftablePro\StockReturnItem\StoreStockReturnItemRequest;
use App\Http\Requests\CraftablePro\StockReturnItem\UpdateStockReturnItemRequest;
use App\Models\StockReturnItem;
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

class StockReturnItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexStockReturnItemRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $stockReturnItemsQuery = QueryBuilder::for(StockReturnItem::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'stock_return_id', 'item_id', 'quantity', 'supplier_price_before_tax', 'supplier_tax_value', 'supplier_price_after_tax', 'supplier_discount_value', 'return_amount', 'description', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'stock_return_id', 'item_id', 'quantity', 'supplier_price_before_tax', 'supplier_tax_value', 'supplier_price_after_tax', 'supplier_discount_value', 'return_amount', 'description', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($stockReturnItemsQuery->select(['id'])->pluck('id'));
        }

        $stockReturnItems = $stockReturnItemsQuery
            ->with(['item:id,name', 'stockReturn:id,description'])
            ->select('id', 'stock_return_id', 'item_id', 'quantity', 'supplier_price_before_tax', 'supplier_tax_value', 'supplier_price_after_tax', 'supplier_discount_value', 'return_amount', 'description', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('stockReturnItems_url', $request->fullUrl());

        return Inertia::render('StockReturnItem/Index', [
            'stockReturnItems' => $stockReturnItems,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateStockReturnItemRequest $request): Response
    {
        return Inertia::render('StockReturnItem/Create', [
            'items' => \App\Models\Item::orderBy('name')->get(['id', 'name']),
            'stock_returns' => \App\Models\StockReturn::orderBy('id')->get(['id', 'description'])->map(fn ($m) => ['id' => $m->id, 'description' => '#' . $m->id . ' ' . ($m->description ?? '')]),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreStockReturnItemRequest $request): RedirectResponse
    {
        $stockReturnItem = StockReturnItem::create($request->validated());
        
        return redirect()->route('craftable-pro.stock-return-items.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditStockReturnItemRequest $request, StockReturnItem $stockReturnItem): Response
    {
        
        return Inertia::render('StockReturnItem/Edit', [
            'stockReturnItem' => $stockReturnItem,
            'items' => \App\Models\Item::orderBy('name')->get(['id', 'name']),
            'stock_returns' => \App\Models\StockReturn::orderBy('id')->get(['id', 'description'])->map(fn ($m) => ['id' => $m->id, 'description' => '#' . $m->id . ' ' . ($m->description ?? '')]),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStockReturnItemRequest $request, StockReturnItem $stockReturnItem): RedirectResponse
    {
        $stockReturnItem->update($request->validated());
        
        if (session('stockReturnItems_url')) {
            return redirect(session('stockReturnItems_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.stock-return-items.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyStockReturnItemRequest $request, StockReturnItem $stockReturnItem): RedirectResponse
    {
        
        $stockReturnItem->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyStockReturnItemRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    StockReturnItem::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                StockReturnItem::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
