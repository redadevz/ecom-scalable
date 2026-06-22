<?php

namespace App\Http\Controllers\CraftablePro;

use App\Exports\CraftablePro\OrderExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\Order\BulkDestroyOrderRequest;
use App\Http\Requests\CraftablePro\Order\CreateOrderRequest;
use App\Http\Requests\CraftablePro\Order\DestroyOrderRequest;
use App\Http\Requests\CraftablePro\Order\EditOrderRequest;
use App\Http\Requests\CraftablePro\Order\ExportOrderRequest;
use App\Http\Requests\CraftablePro\Order\IndexOrderRequest;
use App\Http\Requests\CraftablePro\Order\StoreOrderRequest;
use App\Http\Requests\CraftablePro\Order\UpdateOrderRequest;
use App\Models\Order;
use Brackets\CraftablePro\Queries\Filters\FuzzyFilter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexOrderRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $ordersQuery = QueryBuilder::for(Order::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'user_id', 'status', 'total'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'user_id', 'status', 'total', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($ordersQuery->select(['id'])->pluck('id'));
        }

        $orders = $ordersQuery
            ->with([])
            ->select('id', 'user_id', 'status', 'total', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('orders_url', $request->fullUrl());

        return Inertia::render('Order/Index', [
            'orders' => $orders,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateOrderRequest $request): Response
    {
        return Inertia::render('Order/Create', [
            
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $order = Order::create($request->validated());
        
        return redirect()->route('craftable-pro.orders.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditOrderRequest $request, Order $order): Response
    {
        
        return Inertia::render('Order/Edit', [
            'order' => $order,
            
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order): RedirectResponse
    {
        $order->update($request->validated());
        
        if (session('orders_url')) {
            return redirect(session('orders_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.orders.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyOrderRequest $request, Order $order): RedirectResponse
    {
        
        $order->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyOrderRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    Order::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                Order::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Export
     */
    public function export(ExportOrderRequest $request): BinaryFileResponse
    {
        return Excel::download(new OrderExport($request->all()), 'orders-'.now()->format("dmYHi").'.xlsx');
    }
}
