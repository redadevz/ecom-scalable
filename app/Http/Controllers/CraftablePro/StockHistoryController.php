<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\StockHistory\BulkDestroyStockHistoryRequest;
use App\Http\Requests\CraftablePro\StockHistory\CreateStockHistoryRequest;
use App\Http\Requests\CraftablePro\StockHistory\DestroyStockHistoryRequest;
use App\Http\Requests\CraftablePro\StockHistory\EditStockHistoryRequest;
use App\Http\Requests\CraftablePro\StockHistory\IndexStockHistoryRequest;
use App\Http\Requests\CraftablePro\StockHistory\StoreStockHistoryRequest;
use App\Http\Requests\CraftablePro\StockHistory\UpdateStockHistoryRequest;
use App\Models\StockHistory;
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

class StockHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexStockHistoryRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $stockHistoriesQuery = QueryBuilder::for(StockHistory::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'store_id', 'item_id', 'document_id', 'initial_stock_quantity', 'initial_item_cost', 'is_stock_entry', 'quantity', 'current_stock_quantity', 'current_item_cost', 'description', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'store_id', 'item_id', 'document_id', 'initial_stock_quantity', 'initial_item_cost', 'is_stock_entry', 'quantity', 'current_stock_quantity', 'current_item_cost', 'description', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($stockHistoriesQuery->select(['id'])->pluck('id'));
        }

        $stockHistories = $stockHistoriesQuery
            ->with([])
            ->select('id', 'store_id', 'item_id', 'document_id', 'initial_stock_quantity', 'initial_item_cost', 'is_stock_entry', 'quantity', 'current_stock_quantity', 'current_item_cost', 'description', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('stockHistories_url', $request->fullUrl());

        return Inertia::render('StockHistory/Index', [
            'stockHistories' => $stockHistories,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateStockHistoryRequest $request): Response
    {
        return Inertia::render('StockHistory/Create', [
            
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreStockHistoryRequest $request): RedirectResponse
    {
        $stockHistory = StockHistory::create($request->validated());
        
        return redirect()->route('craftable-pro.stock-histories.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditStockHistoryRequest $request, StockHistory $stockHistory): Response
    {
        
        return Inertia::render('StockHistory/Edit', [
            'stockHistory' => $stockHistory,
            
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStockHistoryRequest $request, StockHistory $stockHistory): RedirectResponse
    {
        $stockHistory->update($request->validated());
        
        if (session('stockHistories_url')) {
            return redirect(session('stockHistories_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.stock-histories.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyStockHistoryRequest $request, StockHistory $stockHistory): RedirectResponse
    {
        
        $stockHistory->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyStockHistoryRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    StockHistory::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                StockHistory::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
