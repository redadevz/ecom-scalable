<?php

namespace App\Http\Controllers\CraftablePro;

use App\Exports\CraftablePro\OrderItemExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\OrderItem\BulkDestroyOrderItemRequest;
use App\Http\Requests\CraftablePro\OrderItem\CreateOrderItemRequest;
use App\Http\Requests\CraftablePro\OrderItem\DestroyOrderItemRequest;
use App\Http\Requests\CraftablePro\OrderItem\EditOrderItemRequest;
use App\Http\Requests\CraftablePro\OrderItem\ExportOrderItemRequest;
use App\Http\Requests\CraftablePro\OrderItem\IndexOrderItemRequest;
use App\Http\Requests\CraftablePro\OrderItem\StoreOrderItemRequest;
use App\Http\Requests\CraftablePro\OrderItem\UpdateOrderItemRequest;
use App\Models\OrderItem;
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

class OrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexOrderItemRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $orderItemsQuery = QueryBuilder::for(OrderItem::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'order_id', 'product_id', 'product_name', 'quantity', 'unit_price'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'order_id', 'product_id', 'product_name', 'quantity', 'unit_price', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($orderItemsQuery->select(['id'])->pluck('id'));
        }

        $orderItems = $orderItemsQuery
            ->with([])
            ->select('id', 'order_id', 'product_id', 'product_name', 'quantity', 'unit_price', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('orderItems_url', $request->fullUrl());

        return Inertia::render('OrderItem/Index', [
            'orderItems' => $orderItems,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateOrderItemRequest $request): Response
    {
        return Inertia::render('OrderItem/Create', [
            
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreOrderItemRequest $request): RedirectResponse
    {
        $orderItem = OrderItem::create($request->validated());
        
        return redirect()->route('craftable-pro.order-items.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditOrderItemRequest $request, OrderItem $orderItem): Response
    {
        
        return Inertia::render('OrderItem/Edit', [
            'orderItem' => $orderItem,
            
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderItemRequest $request, OrderItem $orderItem): RedirectResponse
    {
        $orderItem->update($request->validated());
        
        if (session('orderItems_url')) {
            return redirect(session('orderItems_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.order-items.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyOrderItemRequest $request, OrderItem $orderItem): RedirectResponse
    {
        
        $orderItem->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyOrderItemRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    OrderItem::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                OrderItem::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Export
     */
    public function export(ExportOrderItemRequest $request): BinaryFileResponse
    {
        return Excel::download(new OrderItemExport($request->all()), 'orderitems-'.now()->format("dmYHi").'.xlsx');
    }
}
