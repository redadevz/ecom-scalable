<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\OrderDiscount\BulkDestroyOrderDiscountRequest;
use App\Http\Requests\CraftablePro\OrderDiscount\CreateOrderDiscountRequest;
use App\Http\Requests\CraftablePro\OrderDiscount\DestroyOrderDiscountRequest;
use App\Http\Requests\CraftablePro\OrderDiscount\EditOrderDiscountRequest;
use App\Http\Requests\CraftablePro\OrderDiscount\IndexOrderDiscountRequest;
use App\Http\Requests\CraftablePro\OrderDiscount\StoreOrderDiscountRequest;
use App\Http\Requests\CraftablePro\OrderDiscount\UpdateOrderDiscountRequest;
use App\Models\OrderDiscount;
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

class OrderDiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexOrderDiscountRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $orderDiscountsQuery = QueryBuilder::for(OrderDiscount::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'discount_id', 'order_id', 'value', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'discount_id', 'order_id', 'value', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($orderDiscountsQuery->select(['id'])->pluck('id'));
        }

        $orderDiscounts = $orderDiscountsQuery
            ->with([])
            ->select('id', 'discount_id', 'order_id', 'value', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('orderDiscounts_url', $request->fullUrl());

        return Inertia::render('OrderDiscount/Index', [
            'orderDiscounts' => $orderDiscounts,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateOrderDiscountRequest $request): Response
    {
        return Inertia::render('OrderDiscount/Create', [
            'discounts' => \App\Models\Discount::orderBy('id')->get(['id', 'description'])->map(fn ($m) => ['id' => $m->id, 'description' => '#' . $m->id . ' ' . ($m->description ?? '')]),
            'order_headers' => \App\Models\OrderHeader::orderBy('order_no')->get(['id', 'order_no']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreOrderDiscountRequest $request): RedirectResponse
    {
        $orderDiscount = OrderDiscount::create($request->validated());
        
        return redirect()->route('craftable-pro.order-discounts.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditOrderDiscountRequest $request, OrderDiscount $orderDiscount): Response
    {
        
        return Inertia::render('OrderDiscount/Edit', [
            'orderDiscount' => $orderDiscount,
            'discounts' => \App\Models\Discount::orderBy('id')->get(['id', 'description'])->map(fn ($m) => ['id' => $m->id, 'description' => '#' . $m->id . ' ' . ($m->description ?? '')]),
            'order_headers' => \App\Models\OrderHeader::orderBy('order_no')->get(['id', 'order_no']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderDiscountRequest $request, OrderDiscount $orderDiscount): RedirectResponse
    {
        $orderDiscount->update($request->validated());
        
        if (session('orderDiscounts_url')) {
            return redirect(session('orderDiscounts_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.order-discounts.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyOrderDiscountRequest $request, OrderDiscount $orderDiscount): RedirectResponse
    {
        
        $orderDiscount->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyOrderDiscountRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    OrderDiscount::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                OrderDiscount::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
