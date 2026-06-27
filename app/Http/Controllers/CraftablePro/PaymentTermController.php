<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\PaymentTerm\BulkDestroyPaymentTermRequest;
use App\Http\Requests\CraftablePro\PaymentTerm\CreatePaymentTermRequest;
use App\Http\Requests\CraftablePro\PaymentTerm\DestroyPaymentTermRequest;
use App\Http\Requests\CraftablePro\PaymentTerm\EditPaymentTermRequest;
use App\Http\Requests\CraftablePro\PaymentTerm\IndexPaymentTermRequest;
use App\Http\Requests\CraftablePro\PaymentTerm\StorePaymentTermRequest;
use App\Http\Requests\CraftablePro\PaymentTerm\UpdatePaymentTermRequest;
use App\Models\PaymentTerm;
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

class PaymentTermController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexPaymentTermRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $paymentTermsQuery = QueryBuilder::for(PaymentTerm::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'sale_channel_id', 'delivery_type_id', 'payment_method_id', 'payment_time_id', 'is_allowed', 'is_active', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'sale_channel_id', 'delivery_type_id', 'payment_method_id', 'payment_time_id', 'is_allowed', 'is_active', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($paymentTermsQuery->select(['id'])->pluck('id'));
        }

        $paymentTerms = $paymentTermsQuery
            ->with([])
            ->select('id', 'sale_channel_id', 'delivery_type_id', 'payment_method_id', 'payment_time_id', 'is_allowed', 'is_active', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('paymentTerms_url', $request->fullUrl());

        return Inertia::render('PaymentTerm/Index', [
            'paymentTerms' => $paymentTerms,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreatePaymentTermRequest $request): Response
    {
        return Inertia::render('PaymentTerm/Create', [
            'delivery_types' => \App\Models\DeliveryType::orderBy('name')->get(['id', 'name']),
            'payment_methods' => \App\Models\PaymentMethod::orderBy('name')->get(['id', 'name']),
            'payment_times' => \App\Models\PaymentTime::orderBy('name')->get(['id', 'name']),
            'sale_channels' => \App\Models\SaleChannel::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StorePaymentTermRequest $request): RedirectResponse
    {
        $paymentTerm = PaymentTerm::create($request->validated());
        
        return redirect()->route('craftable-pro.payment-terms.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditPaymentTermRequest $request, PaymentTerm $paymentTerm): Response
    {
        
        return Inertia::render('PaymentTerm/Edit', [
            'paymentTerm' => $paymentTerm,
            'delivery_types' => \App\Models\DeliveryType::orderBy('name')->get(['id', 'name']),
            'payment_methods' => \App\Models\PaymentMethod::orderBy('name')->get(['id', 'name']),
            'payment_times' => \App\Models\PaymentTime::orderBy('name')->get(['id', 'name']),
            'sale_channels' => \App\Models\SaleChannel::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentTermRequest $request, PaymentTerm $paymentTerm): RedirectResponse
    {
        $paymentTerm->update($request->validated());
        
        if (session('paymentTerms_url')) {
            return redirect(session('paymentTerms_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.payment-terms.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyPaymentTermRequest $request, PaymentTerm $paymentTerm): RedirectResponse
    {
        
        $paymentTerm->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyPaymentTermRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    PaymentTerm::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                PaymentTerm::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
