<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\OrderStatus\BulkDestroyOrderStatusRequest;
use App\Http\Requests\CraftablePro\OrderStatus\CreateOrderStatusRequest;
use App\Http\Requests\CraftablePro\OrderStatus\DestroyOrderStatusRequest;
use App\Http\Requests\CraftablePro\OrderStatus\EditOrderStatusRequest;
use App\Http\Requests\CraftablePro\OrderStatus\IndexOrderStatusRequest;
use App\Http\Requests\CraftablePro\OrderStatus\StoreOrderStatusRequest;
use App\Http\Requests\CraftablePro\OrderStatus\UpdateOrderStatusRequest;
use App\Models\OrderStatus;
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

class OrderStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexOrderStatusRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $orderStatusesQuery = QueryBuilder::for(OrderStatus::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'name', 'description', 'is_default'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'name', 'description', 'is_default', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($orderStatusesQuery->select(['id'])->pluck('id'));
        }

        $orderStatuses = $orderStatusesQuery
            ->with([])
            ->select('id', 'name', 'description', 'is_default', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('orderStatuses_url', $request->fullUrl());

        return Inertia::render('OrderStatus/Index', [
            'orderStatuses' => $orderStatuses,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateOrderStatusRequest $request): Response
    {
        return Inertia::render('OrderStatus/Create', [
            
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreOrderStatusRequest $request): RedirectResponse
    {
        $orderStatus = OrderStatus::create($request->validated());
        
        return redirect()->route('craftable-pro.order-statuses.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditOrderStatusRequest $request, OrderStatus $orderStatus): Response
    {
        
        return Inertia::render('OrderStatus/Edit', [
            'orderStatus' => $orderStatus,
            
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderStatusRequest $request, OrderStatus $orderStatus): RedirectResponse
    {
        $orderStatus->update($request->validated());
        
        if (session('orderStatuses_url')) {
            return redirect(session('orderStatuses_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.order-statuses.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyOrderStatusRequest $request, OrderStatus $orderStatus): RedirectResponse
    {
        
        $orderStatus->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyOrderStatusRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    OrderStatus::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                OrderStatus::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
