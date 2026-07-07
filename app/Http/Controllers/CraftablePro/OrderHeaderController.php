<?php

namespace App\Http\Controllers\CraftablePro;

use Illuminate\Support\Facades\Gate;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\OrderHeader\BulkDestroyOrderHeaderRequest;
use App\Http\Requests\CraftablePro\OrderHeader\CreateOrderHeaderRequest;
use App\Http\Requests\CraftablePro\OrderHeader\DestroyOrderHeaderRequest;
use App\Http\Requests\CraftablePro\OrderHeader\EditOrderHeaderRequest;
use App\Http\Requests\CraftablePro\OrderHeader\IndexOrderHeaderRequest;
use App\Http\Requests\CraftablePro\OrderHeader\StoreOrderHeaderRequest;
use App\Http\Requests\CraftablePro\OrderHeader\UpdateOrderHeaderRequest;
use App\Models\OrderHeader;
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
use App\Exceptions\InsufficientStockException;
use App\Services\InvoiceService;
use App\Services\OrderService;

class OrderHeaderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexOrderHeaderRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $orderHeadersQuery = QueryBuilder::for(OrderHeader::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'store_id', 'sale_channel_id', 'delivery_type_id', 'payment_method_id', 'payment_time_id', 'customer_id', 'loyalty_card_id', 'created_by', 'approved_by', 'managed_by', 'order_no', 'customer_notes', 'price_before_tax', 'total_tax_value', 'price_after_tax', 'price_before_discount', 'order_items_discount', 'order_discount', 'total_discount_value', 'price_after_discount', 'price_adjustment', 'price_adjustment_reason', 'price', 'latest_status', 'is_submitted', 'is_approved', 'is_canceled', 'cancel_reason', 'is_scheduled', 'is_ready', 'is_delivered', 'is_paid', 'is_completed', 'return_required', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'store_id', 'sale_channel_id', 'delivery_type_id', 'payment_method_id', 'payment_time_id', 'customer_id', 'loyalty_card_id', 'created_by', 'approved_by', 'managed_by', 'order_no', 'customer_notes', 'price_before_tax', 'total_tax_value', 'price_after_tax', 'price_before_discount', 'order_items_discount', 'order_discount', 'total_discount_value', 'price_after_discount', 'price_adjustment', 'price_adjustment_reason', 'price', 'latest_status', 'latest_status_update', 'is_submitted', 'submitted_time', 'is_approved', 'approved_time', 'is_canceled', 'canceled_time', 'cancel_reason', 'is_scheduled', 'scheduled_time', 'is_ready', 'ready_time', 'is_delivered', 'delivered_time', 'is_paid', 'payment_time', 'is_completed', 'completed_time', 'return_required', 'return_time', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($orderHeadersQuery->select(['id'])->pluck('id'));
        }

        $orderHeaders = $orderHeadersQuery
            ->with(['customer:id,first_name,last_name,company_name,is_company'])
            ->select('id', 'store_id', 'sale_channel_id', 'delivery_type_id', 'payment_method_id', 'payment_time_id', 'customer_id', 'loyalty_card_id', 'created_by', 'approved_by', 'managed_by', 'order_no', 'customer_notes', 'price_before_tax', 'total_tax_value', 'price_after_tax', 'price_before_discount', 'order_items_discount', 'order_discount', 'total_discount_value', 'price_after_discount', 'price_adjustment', 'price_adjustment_reason', 'price', 'latest_status', 'latest_status_update', 'is_submitted', 'submitted_time', 'is_approved', 'approved_time', 'is_canceled', 'canceled_time', 'cancel_reason', 'is_scheduled', 'scheduled_time', 'is_ready', 'ready_time', 'is_delivered', 'delivered_time', 'is_paid', 'payment_time', 'is_completed', 'completed_time', 'return_required', 'return_time', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('orderHeaders_url', $request->fullUrl());

        return Inertia::render('OrderHeader/Index', [
            'orderHeaders' => $orderHeaders,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateOrderHeaderRequest $request): Response
    {
        return Inertia::render('OrderHeader/Create', [
            'craftable_pro_users' => \Brackets\CraftablePro\Models\CraftableProUser::orderBy('email')->get(['id', 'email']),
            'customers' => \App\Models\Customer::orderBy('code')->get(['id', 'code']),
            'delivery_types' => \App\Models\DeliveryType::orderBy('name')->get(['id', 'name']),
            'loyalty_cards' => \App\Models\LoyaltyCard::orderBy('code')->get(['id', 'code']),
            'payment_methods' => \App\Models\PaymentMethod::orderBy('name')->get(['id', 'name']),
            'payment_times' => \App\Models\PaymentTime::orderBy('name')->get(['id', 'name']),
            'sale_channels' => \App\Models\SaleChannel::orderBy('name')->get(['id', 'name']),
            'stores' => \App\Models\Store::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreOrderHeaderRequest $request): RedirectResponse
    {
        $orderHeader = OrderHeader::create($request->validated());
        
        return redirect()->route('craftable-pro.order-headers.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditOrderHeaderRequest $request, OrderHeader $orderHeader): Response
    {
        
        return Inertia::render('OrderHeader/Edit', [
            'orderHeader' => $orderHeader,
            'craftable_pro_users' => \Brackets\CraftablePro\Models\CraftableProUser::orderBy('email')->get(['id', 'email']),
            'customers' => \App\Models\Customer::orderBy('code')->get(['id', 'code']),
            'delivery_types' => \App\Models\DeliveryType::orderBy('name')->get(['id', 'name']),
            'loyalty_cards' => \App\Models\LoyaltyCard::orderBy('code')->get(['id', 'code']),
            'payment_methods' => \App\Models\PaymentMethod::orderBy('name')->get(['id', 'name']),
            'payment_times' => \App\Models\PaymentTime::orderBy('name')->get(['id', 'name']),
            'sale_channels' => \App\Models\SaleChannel::orderBy('name')->get(['id', 'name']),
            'stores' => \App\Models\Store::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Read-only detail page.
     */
    public function show(OrderHeader $orderHeader): Response
    {
        Gate::authorize('craftable-pro.order-headers.index');

        $orderHeader->load([
            'customer:id,code,first_name,last_name,company_name,is_company',
            'store:id,name',
            'orderLines:id,order_id,item_id,quantity,price_after_tax,price,is_canceled',
            'orderLines.item:id,name,sku_code',
            'orderStatusHistories:id,order_id,order_status_id,start_time,end_time',
            'orderStatusHistories.orderStatus:id,name',
            'invoices:id,order_id,invoice_no,is_paid,created_at',
        ]);

        return Inertia::render('OrderHeader/Show', [
            'order' => $orderHeader,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderHeaderRequest $request, OrderHeader $orderHeader): RedirectResponse
    {
        $orderHeader->update($request->validated());
        
        if (session('orderHeaders_url')) {
            return redirect(session('orderHeaders_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.order-headers.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyOrderHeaderRequest $request, OrderHeader $orderHeader): RedirectResponse
    {
        
        $orderHeader->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyOrderHeaderRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    OrderHeader::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                OrderHeader::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    public function confirm(OrderHeader $orderHeader, OrderService $orders)
{
    Gate::authorize('craftable-pro.order-headers.confirm');
    try {
        $orders->confirm($orderHeader);

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    } catch (InsufficientStockException $e) {
        return redirect()->back()->with(['error' => ___('craftable-pro', 'Insufficient stock')]);
    }
}


    public function cancel(OrderHeader $orderHeader, OrderService $orders){
        Gate::authorize('craftable-pro.order-headers.cancel');
        try{
            $orders->cancel($orderHeader);

            return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }catch(\RuntimeException $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);

        }
    }

    public function invoice(OrderHeader $orderHeader, InvoiceService $invoices){
        Gate::authorize('craftable-pro.order-headers.invoice');
        try{
            $invoices->generate($orderHeader);
            return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }catch(\App\Exceptions\OrderAlreadyInvoicedException | \RuntimeException $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

}
