<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\StockReturn\BulkDestroyStockReturnRequest;
use App\Http\Requests\CraftablePro\StockReturn\CreateStockReturnRequest;
use App\Http\Requests\CraftablePro\StockReturn\DestroyStockReturnRequest;
use App\Http\Requests\CraftablePro\StockReturn\EditStockReturnRequest;
use App\Http\Requests\CraftablePro\StockReturn\IndexStockReturnRequest;
use App\Http\Requests\CraftablePro\StockReturn\StoreStockReturnRequest;
use App\Http\Requests\CraftablePro\StockReturn\UpdateStockReturnRequest;
use App\Models\StockReturn;
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

class StockReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexStockReturnRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $stockReturnsQuery = QueryBuilder::for(StockReturn::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'store_id', 'purchase_id', 'description', 'is_paid', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'store_id', 'purchase_id', 'exit_stock_time', 'description', 'is_paid', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($stockReturnsQuery->select(['id'])->pluck('id'));
        }

        $stockReturns = $stockReturnsQuery
            ->with([])
            ->select('id', 'store_id', 'purchase_id', 'exit_stock_time', 'description', 'is_paid', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('stockReturns_url', $request->fullUrl());

        return Inertia::render('StockReturn/Index', [
            'stockReturns' => $stockReturns,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateStockReturnRequest $request): Response
    {
        return Inertia::render('StockReturn/Create', [
            'purchases' => \App\Models\Purchase::orderBy('id')->get(['id']),
            'stores' => \App\Models\Store::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreStockReturnRequest $request): RedirectResponse
    {
        $stockReturn = StockReturn::create($request->validated());
        
        return redirect()->route('craftable-pro.stock-returns.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditStockReturnRequest $request, StockReturn $stockReturn): Response
    {
        
        return Inertia::render('StockReturn/Edit', [
            'stockReturn' => $stockReturn,
            'purchases' => \App\Models\Purchase::orderBy('id')->get(['id']),
            'stores' => \App\Models\Store::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStockReturnRequest $request, StockReturn $stockReturn): RedirectResponse
    {
        $stockReturn->update($request->validated());
        
        if (session('stockReturns_url')) {
            return redirect(session('stockReturns_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.stock-returns.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyStockReturnRequest $request, StockReturn $stockReturn): RedirectResponse
    {
        
        $stockReturn->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyStockReturnRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    StockReturn::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                StockReturn::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
