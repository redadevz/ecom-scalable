<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\PaymentMethod\BulkDestroyPaymentMethodRequest;
use App\Http\Requests\CraftablePro\PaymentMethod\CreatePaymentMethodRequest;
use App\Http\Requests\CraftablePro\PaymentMethod\DestroyPaymentMethodRequest;
use App\Http\Requests\CraftablePro\PaymentMethod\EditPaymentMethodRequest;
use App\Http\Requests\CraftablePro\PaymentMethod\IndexPaymentMethodRequest;
use App\Http\Requests\CraftablePro\PaymentMethod\StorePaymentMethodRequest;
use App\Http\Requests\CraftablePro\PaymentMethod\UpdatePaymentMethodRequest;
use App\Models\PaymentMethod;
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

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexPaymentMethodRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $paymentMethodsQuery = QueryBuilder::for(PaymentMethod::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'name', 'code', 'sequence_no', 'is_active', 'is_customer_required', 'description'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'name', 'code', 'sequence_no', 'is_active', 'is_customer_required', 'description', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($paymentMethodsQuery->select(['id'])->pluck('id'));
        }

        $paymentMethods = $paymentMethodsQuery
            ->with([])
            ->select('id', 'name', 'code', 'sequence_no', 'is_active', 'is_customer_required', 'description', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('paymentMethods_url', $request->fullUrl());

        return Inertia::render('PaymentMethod/Index', [
            'paymentMethods' => $paymentMethods,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreatePaymentMethodRequest $request): Response
    {
        return Inertia::render('PaymentMethod/Create', [
            
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StorePaymentMethodRequest $request): RedirectResponse
    {
        $paymentMethod = PaymentMethod::create($request->validated());
        
        return redirect()->route('craftable-pro.payment-methods.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditPaymentMethodRequest $request, PaymentMethod $paymentMethod): Response
    {
        
        return Inertia::render('PaymentMethod/Edit', [
            'paymentMethod' => $paymentMethod,
            
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentMethodRequest $request, PaymentMethod $paymentMethod): RedirectResponse
    {
        $paymentMethod->update($request->validated());
        
        if (session('paymentMethods_url')) {
            return redirect(session('paymentMethods_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.payment-methods.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyPaymentMethodRequest $request, PaymentMethod $paymentMethod): RedirectResponse
    {
        
        $paymentMethod->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyPaymentMethodRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    PaymentMethod::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                PaymentMethod::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
