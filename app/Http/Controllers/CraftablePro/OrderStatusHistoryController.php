<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\OrderStatusHistory\BulkDestroyOrderStatusHistoryRequest;
use App\Http\Requests\CraftablePro\OrderStatusHistory\CreateOrderStatusHistoryRequest;
use App\Http\Requests\CraftablePro\OrderStatusHistory\DestroyOrderStatusHistoryRequest;
use App\Http\Requests\CraftablePro\OrderStatusHistory\EditOrderStatusHistoryRequest;
use App\Http\Requests\CraftablePro\OrderStatusHistory\IndexOrderStatusHistoryRequest;
use App\Http\Requests\CraftablePro\OrderStatusHistory\StoreOrderStatusHistoryRequest;
use App\Http\Requests\CraftablePro\OrderStatusHistory\UpdateOrderStatusHistoryRequest;
use App\Models\OrderStatusHistory;
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

class OrderStatusHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexOrderStatusHistoryRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $orderStatusHistoriesQuery = QueryBuilder::for(OrderStatusHistory::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'order_id', 'order_status_id'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'order_id', 'order_status_id', 'start_time', 'end_time', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($orderStatusHistoriesQuery->select(['id'])->pluck('id'));
        }

        $orderStatusHistories = $orderStatusHistoriesQuery
            ->with([])
            ->select('id', 'order_id', 'order_status_id', 'start_time', 'end_time', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('orderStatusHistories_url', $request->fullUrl());

        return Inertia::render('OrderStatusHistory/Index', [
            'orderStatusHistories' => $orderStatusHistories,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateOrderStatusHistoryRequest $request): Response
    {
        return Inertia::render('OrderStatusHistory/Create', [
            'order_headers' => \App\Models\OrderHeader::orderBy('order_no')->get(['id', 'order_no']),
            'order_statuses' => \App\Models\OrderStatus::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreOrderStatusHistoryRequest $request): RedirectResponse
    {
        $orderStatusHistory = OrderStatusHistory::create($request->validated());
        
        return redirect()->route('craftable-pro.order-status-histories.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditOrderStatusHistoryRequest $request, OrderStatusHistory $orderStatusHistory): Response
    {
        
        return Inertia::render('OrderStatusHistory/Edit', [
            'orderStatusHistory' => $orderStatusHistory,
            'order_headers' => \App\Models\OrderHeader::orderBy('order_no')->get(['id', 'order_no']),
            'order_statuses' => \App\Models\OrderStatus::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderStatusHistoryRequest $request, OrderStatusHistory $orderStatusHistory): RedirectResponse
    {
        $orderStatusHistory->update($request->validated());
        
        if (session('orderStatusHistories_url')) {
            return redirect(session('orderStatusHistories_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.order-status-histories.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyOrderStatusHistoryRequest $request, OrderStatusHistory $orderStatusHistory): RedirectResponse
    {
        
        $orderStatusHistory->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyOrderStatusHistoryRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    OrderStatusHistory::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                OrderStatusHistory::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
