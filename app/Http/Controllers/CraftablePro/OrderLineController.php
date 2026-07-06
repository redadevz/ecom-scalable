<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\OrderLine\BulkDestroyOrderLineRequest;
use App\Http\Requests\CraftablePro\OrderLine\CreateOrderLineRequest;
use App\Http\Requests\CraftablePro\OrderLine\DestroyOrderLineRequest;
use App\Http\Requests\CraftablePro\OrderLine\EditOrderLineRequest;
use App\Http\Requests\CraftablePro\OrderLine\IndexOrderLineRequest;
use App\Http\Requests\CraftablePro\OrderLine\StoreOrderLineRequest;
use App\Http\Requests\CraftablePro\OrderLine\UpdateOrderLineRequest;
use App\Models\OrderLine;
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

class OrderLineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexOrderLineRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $orderLinesQuery = QueryBuilder::for(OrderLine::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'store_id', 'order_id', 'item_id', 'line_no', 'description', 'customer_notes', 'quantity', 'current_item_cost', 'markup_percentage', 'price_before_tax', 'tax_value', 'price_after_tax', 'price_before_discount', 'discount_value', 'price_after_discount', 'price_adjustment', 'price_adjustment_reason', 'price', 'is_canceled', 'cancel_reason', 'return_required', 'return_quantity', 'customer_review', 'customer_like', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'store_id', 'order_id', 'item_id', 'line_no', 'description', 'customer_notes', 'quantity', 'current_item_cost', 'markup_percentage', 'price_before_tax', 'tax_value', 'price_after_tax', 'price_before_discount', 'discount_value', 'price_after_discount', 'price_adjustment', 'price_adjustment_reason', 'price', 'is_canceled', 'canceled_time', 'cancel_reason', 'return_required', 'return_quantity', 'return_time', 'customer_review', 'customer_like', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($orderLinesQuery->select(['id'])->pluck('id'));
        }

        $orderLines = $orderLinesQuery
            ->with(['item:id,name', 'order:id,order_no'])
            ->select('id', 'store_id', 'order_id', 'item_id', 'line_no', 'description', 'customer_notes', 'quantity', 'current_item_cost', 'markup_percentage', 'price_before_tax', 'tax_value', 'price_after_tax', 'price_before_discount', 'discount_value', 'price_after_discount', 'price_adjustment', 'price_adjustment_reason', 'price', 'is_canceled', 'canceled_time', 'cancel_reason', 'return_required', 'return_quantity', 'return_time', 'customer_review', 'customer_like', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('orderLines_url', $request->fullUrl());

        return Inertia::render('OrderLine/Index', [
            'orderLines' => $orderLines,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateOrderLineRequest $request): Response
    {
        return Inertia::render('OrderLine/Create', [
            'items' => \App\Models\Item::orderBy('name')->get(['id', 'name']),
            'order_headers' => \App\Models\OrderHeader::orderBy('order_no')->get(['id', 'order_no']),
            'stores' => \App\Models\Store::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreOrderLineRequest $request): RedirectResponse
    {
        $orderLine = OrderLine::create($request->validated());
        
        return redirect()->route('craftable-pro.order-lines.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditOrderLineRequest $request, OrderLine $orderLine): Response
    {
        
        return Inertia::render('OrderLine/Edit', [
            'orderLine' => $orderLine,
            'items' => \App\Models\Item::orderBy('name')->get(['id', 'name']),
            'order_headers' => \App\Models\OrderHeader::orderBy('order_no')->get(['id', 'order_no']),
            'stores' => \App\Models\Store::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderLineRequest $request, OrderLine $orderLine): RedirectResponse
    {
        $orderLine->update($request->validated());
        
        if (session('orderLines_url')) {
            return redirect(session('orderLines_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.order-lines.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyOrderLineRequest $request, OrderLine $orderLine): RedirectResponse
    {
        
        $orderLine->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyOrderLineRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    OrderLine::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                OrderLine::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
