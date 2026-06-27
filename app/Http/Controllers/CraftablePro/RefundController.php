<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\Refund\BulkDestroyRefundRequest;
use App\Http\Requests\CraftablePro\Refund\CreateRefundRequest;
use App\Http\Requests\CraftablePro\Refund\DestroyRefundRequest;
use App\Http\Requests\CraftablePro\Refund\EditRefundRequest;
use App\Http\Requests\CraftablePro\Refund\IndexRefundRequest;
use App\Http\Requests\CraftablePro\Refund\StoreRefundRequest;
use App\Http\Requests\CraftablePro\Refund\UpdateRefundRequest;
use App\Models\Refund;
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

class RefundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRefundRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $refundsQuery = QueryBuilder::for(Refund::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'sale_return_id', 'payment_method_id', 'refund_no', 'amount', 'cash_paid', 'cash_change', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'sale_return_id', 'payment_method_id', 'refund_no', 'amount', 'cash_paid', 'cash_change', 'refund_time', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($refundsQuery->select(['id'])->pluck('id'));
        }

        $refunds = $refundsQuery
            ->with([])
            ->select('id', 'sale_return_id', 'payment_method_id', 'refund_no', 'amount', 'cash_paid', 'cash_change', 'refund_time', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('refunds_url', $request->fullUrl());

        return Inertia::render('Refund/Index', [
            'refunds' => $refunds,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateRefundRequest $request): Response
    {
        return Inertia::render('Refund/Create', [
            'payment_methods' => \App\Models\PaymentMethod::orderBy('name')->get(['id', 'name']),
            'sale_returns' => \App\Models\SaleReturn::orderBy('id')->get(['id']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreRefundRequest $request): RedirectResponse
    {
        $refund = Refund::create($request->validated());
        
        return redirect()->route('craftable-pro.refunds.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditRefundRequest $request, Refund $refund): Response
    {
        
        return Inertia::render('Refund/Edit', [
            'refund' => $refund,
            'payment_methods' => \App\Models\PaymentMethod::orderBy('name')->get(['id', 'name']),
            'sale_returns' => \App\Models\SaleReturn::orderBy('id')->get(['id']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRefundRequest $request, Refund $refund): RedirectResponse
    {
        $refund->update($request->validated());
        
        if (session('refunds_url')) {
            return redirect(session('refunds_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.refunds.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyRefundRequest $request, Refund $refund): RedirectResponse
    {
        
        $refund->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyRefundRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    Refund::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                Refund::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
