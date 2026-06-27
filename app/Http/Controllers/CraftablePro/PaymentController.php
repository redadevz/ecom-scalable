<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\Payment\BulkDestroyPaymentRequest;
use App\Http\Requests\CraftablePro\Payment\CreatePaymentRequest;
use App\Http\Requests\CraftablePro\Payment\DestroyPaymentRequest;
use App\Http\Requests\CraftablePro\Payment\EditPaymentRequest;
use App\Http\Requests\CraftablePro\Payment\IndexPaymentRequest;
use App\Http\Requests\CraftablePro\Payment\StorePaymentRequest;
use App\Http\Requests\CraftablePro\Payment\UpdatePaymentRequest;
use App\Models\Payment;
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

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexPaymentRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $paymentsQuery = QueryBuilder::for(Payment::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'invoice_id', 'payment_method_id', 'payment_no', 'amount', 'cash_paid', 'cash_change', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'invoice_id', 'payment_method_id', 'payment_no', 'amount', 'cash_paid', 'cash_change', 'payment_time', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($paymentsQuery->select(['id'])->pluck('id'));
        }

        $payments = $paymentsQuery
            ->with([])
            ->select('id', 'invoice_id', 'payment_method_id', 'payment_no', 'amount', 'cash_paid', 'cash_change', 'payment_time', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('payments_url', $request->fullUrl());

        return Inertia::render('Payment/Index', [
            'payments' => $payments,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreatePaymentRequest $request): Response
    {
        return Inertia::render('Payment/Create', [
            'invoices' => \App\Models\Invoice::orderBy('invoice_no')->get(['id', 'invoice_no']),
            'payment_methods' => \App\Models\PaymentMethod::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StorePaymentRequest $request): RedirectResponse
    {
        $payment = Payment::create($request->validated());
        
        return redirect()->route('craftable-pro.payments.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditPaymentRequest $request, Payment $payment): Response
    {
        
        return Inertia::render('Payment/Edit', [
            'payment' => $payment,
            'invoices' => \App\Models\Invoice::orderBy('invoice_no')->get(['id', 'invoice_no']),
            'payment_methods' => \App\Models\PaymentMethod::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentRequest $request, Payment $payment): RedirectResponse
    {
        $payment->update($request->validated());
        
        if (session('payments_url')) {
            return redirect(session('payments_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.payments.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyPaymentRequest $request, Payment $payment): RedirectResponse
    {
        
        $payment->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyPaymentRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    Payment::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                Payment::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
