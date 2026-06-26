<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\SaleReturn\BulkDestroySaleReturnRequest;
use App\Http\Requests\CraftablePro\SaleReturn\CreateSaleReturnRequest;
use App\Http\Requests\CraftablePro\SaleReturn\DestroySaleReturnRequest;
use App\Http\Requests\CraftablePro\SaleReturn\EditSaleReturnRequest;
use App\Http\Requests\CraftablePro\SaleReturn\IndexSaleReturnRequest;
use App\Http\Requests\CraftablePro\SaleReturn\StoreSaleReturnRequest;
use App\Http\Requests\CraftablePro\SaleReturn\UpdateSaleReturnRequest;
use App\Models\SaleReturn;
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

class SaleReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexSaleReturnRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $saleReturnsQuery = QueryBuilder::for(SaleReturn::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'store_id', 'order_id', 'is_refund_required', 'refund_amount', 'is_refunded', 'description', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'store_id', 'order_id', 'entry_stock_time', 'is_refund_required', 'refund_amount', 'is_refunded', 'refund_time', 'description', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($saleReturnsQuery->select(['id'])->pluck('id'));
        }

        $saleReturns = $saleReturnsQuery
            ->with([])
            ->select('id', 'store_id', 'order_id', 'entry_stock_time', 'is_refund_required', 'refund_amount', 'is_refunded', 'refund_time', 'description', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('saleReturns_url', $request->fullUrl());

        return Inertia::render('SaleReturn/Index', [
            'saleReturns' => $saleReturns,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateSaleReturnRequest $request): Response
    {
        return Inertia::render('SaleReturn/Create', [
            
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreSaleReturnRequest $request): RedirectResponse
    {
        $saleReturn = SaleReturn::create($request->validated());
        
        return redirect()->route('craftable-pro.sale-returns.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditSaleReturnRequest $request, SaleReturn $saleReturn): Response
    {
        
        return Inertia::render('SaleReturn/Edit', [
            'saleReturn' => $saleReturn,
            
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSaleReturnRequest $request, SaleReturn $saleReturn): RedirectResponse
    {
        $saleReturn->update($request->validated());
        
        if (session('saleReturns_url')) {
            return redirect(session('saleReturns_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.sale-returns.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroySaleReturnRequest $request, SaleReturn $saleReturn): RedirectResponse
    {
        
        $saleReturn->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroySaleReturnRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    SaleReturn::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                SaleReturn::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
