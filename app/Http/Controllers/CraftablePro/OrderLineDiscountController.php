<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\OrderLineDiscount\BulkDestroyOrderLineDiscountRequest;
use App\Http\Requests\CraftablePro\OrderLineDiscount\CreateOrderLineDiscountRequest;
use App\Http\Requests\CraftablePro\OrderLineDiscount\DestroyOrderLineDiscountRequest;
use App\Http\Requests\CraftablePro\OrderLineDiscount\EditOrderLineDiscountRequest;
use App\Http\Requests\CraftablePro\OrderLineDiscount\IndexOrderLineDiscountRequest;
use App\Http\Requests\CraftablePro\OrderLineDiscount\StoreOrderLineDiscountRequest;
use App\Http\Requests\CraftablePro\OrderLineDiscount\UpdateOrderLineDiscountRequest;
use App\Models\OrderLineDiscount;
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

class OrderLineDiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexOrderLineDiscountRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $orderLineDiscountsQuery = QueryBuilder::for(OrderLineDiscount::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'discount_id', 'order_line_id', 'value', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'discount_id', 'order_line_id', 'value', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($orderLineDiscountsQuery->select(['id'])->pluck('id'));
        }

        $orderLineDiscounts = $orderLineDiscountsQuery
            ->with([])
            ->select('id', 'discount_id', 'order_line_id', 'value', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('orderLineDiscounts_url', $request->fullUrl());

        return Inertia::render('OrderLineDiscount/Index', [
            'orderLineDiscounts' => $orderLineDiscounts,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateOrderLineDiscountRequest $request): Response
    {
        return Inertia::render('OrderLineDiscount/Create', [
            'discounts' => \App\Models\Discount::orderBy('id')->get(['id']),
            'order_lines' => \App\Models\OrderLine::orderBy('line_no')->get(['id', 'line_no']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreOrderLineDiscountRequest $request): RedirectResponse
    {
        $orderLineDiscount = OrderLineDiscount::create($request->validated());
        
        return redirect()->route('craftable-pro.order-line-discounts.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditOrderLineDiscountRequest $request, OrderLineDiscount $orderLineDiscount): Response
    {
        
        return Inertia::render('OrderLineDiscount/Edit', [
            'orderLineDiscount' => $orderLineDiscount,
            'discounts' => \App\Models\Discount::orderBy('id')->get(['id']),
            'order_lines' => \App\Models\OrderLine::orderBy('line_no')->get(['id', 'line_no']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderLineDiscountRequest $request, OrderLineDiscount $orderLineDiscount): RedirectResponse
    {
        $orderLineDiscount->update($request->validated());
        
        if (session('orderLineDiscounts_url')) {
            return redirect(session('orderLineDiscounts_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.order-line-discounts.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyOrderLineDiscountRequest $request, OrderLineDiscount $orderLineDiscount): RedirectResponse
    {
        
        $orderLineDiscount->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyOrderLineDiscountRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    OrderLineDiscount::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                OrderLineDiscount::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
