<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\OrderReview\BulkDestroyOrderReviewRequest;
use App\Http\Requests\CraftablePro\OrderReview\CreateOrderReviewRequest;
use App\Http\Requests\CraftablePro\OrderReview\DestroyOrderReviewRequest;
use App\Http\Requests\CraftablePro\OrderReview\EditOrderReviewRequest;
use App\Http\Requests\CraftablePro\OrderReview\IndexOrderReviewRequest;
use App\Http\Requests\CraftablePro\OrderReview\StoreOrderReviewRequest;
use App\Http\Requests\CraftablePro\OrderReview\UpdateOrderReviewRequest;
use App\Models\OrderReview;
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

class OrderReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexOrderReviewRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $orderReviewsQuery = QueryBuilder::for(OrderReview::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'order_id', 'customer_id', 'replied_by', 'rating', 'review', 'reply', 'is_compensated', 'compensation_value', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'order_id', 'customer_id', 'replied_by', 'rating', 'review', 'review_time', 'reply', 'reply_time', 'is_compensated', 'compensation_value', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($orderReviewsQuery->select(['id'])->pluck('id'));
        }

        $orderReviews = $orderReviewsQuery
            ->with(['order:id,order_no', 'customer:id,code'])
            ->select('id', 'order_id', 'customer_id', 'replied_by', 'rating', 'review', 'review_time', 'reply', 'reply_time', 'is_compensated', 'compensation_value', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('orderReviews_url', $request->fullUrl());

        return Inertia::render('OrderReview/Index', [
            'orderReviews' => $orderReviews,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateOrderReviewRequest $request): Response
    {
        return Inertia::render('OrderReview/Create', [
            'customers' => \App\Models\Customer::orderBy('code')->get(['id', 'code']),
            'order_headers' => \App\Models\OrderHeader::orderBy('order_no')->get(['id', 'order_no']),
            'craftable_pro_users' => \Brackets\CraftablePro\Models\CraftableProUser::orderBy('email')->get(['id', 'email']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreOrderReviewRequest $request): RedirectResponse
    {
        $orderReview = OrderReview::create($request->validated());
        
        return redirect()->route('craftable-pro.order-reviews.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditOrderReviewRequest $request, OrderReview $orderReview): Response
    {
        
        return Inertia::render('OrderReview/Edit', [
            'orderReview' => $orderReview,
            'customers' => \App\Models\Customer::orderBy('code')->get(['id', 'code']),
            'order_headers' => \App\Models\OrderHeader::orderBy('order_no')->get(['id', 'order_no']),
            'craftable_pro_users' => \Brackets\CraftablePro\Models\CraftableProUser::orderBy('email')->get(['id', 'email']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderReviewRequest $request, OrderReview $orderReview): RedirectResponse
    {
        $orderReview->update($request->validated());
        
        if (session('orderReviews_url')) {
            return redirect(session('orderReviews_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.order-reviews.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyOrderReviewRequest $request, OrderReview $orderReview): RedirectResponse
    {
        
        $orderReview->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyOrderReviewRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    OrderReview::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                OrderReview::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
