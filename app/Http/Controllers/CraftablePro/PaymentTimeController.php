<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\PaymentTime\BulkDestroyPaymentTimeRequest;
use App\Http\Requests\CraftablePro\PaymentTime\CreatePaymentTimeRequest;
use App\Http\Requests\CraftablePro\PaymentTime\DestroyPaymentTimeRequest;
use App\Http\Requests\CraftablePro\PaymentTime\EditPaymentTimeRequest;
use App\Http\Requests\CraftablePro\PaymentTime\IndexPaymentTimeRequest;
use App\Http\Requests\CraftablePro\PaymentTime\StorePaymentTimeRequest;
use App\Http\Requests\CraftablePro\PaymentTime\UpdatePaymentTimeRequest;
use App\Models\PaymentTime;
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

class PaymentTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexPaymentTimeRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $paymentTimesQuery = QueryBuilder::for(PaymentTime::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'name', 'description', 'is_active'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'name', 'description', 'is_active', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($paymentTimesQuery->select(['id'])->pluck('id'));
        }

        $paymentTimes = $paymentTimesQuery
            ->with([])
            ->select('id', 'name', 'description', 'is_active', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('paymentTimes_url', $request->fullUrl());

        return Inertia::render('PaymentTime/Index', [
            'paymentTimes' => $paymentTimes,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreatePaymentTimeRequest $request): Response
    {
        return Inertia::render('PaymentTime/Create', [
            
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StorePaymentTimeRequest $request): RedirectResponse
    {
        $paymentTime = PaymentTime::create($request->validated());
        
        return redirect()->route('craftable-pro.payment-times.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditPaymentTimeRequest $request, PaymentTime $paymentTime): Response
    {
        
        return Inertia::render('PaymentTime/Edit', [
            'paymentTime' => $paymentTime,
            
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentTimeRequest $request, PaymentTime $paymentTime): RedirectResponse
    {
        $paymentTime->update($request->validated());
        
        if (session('paymentTimes_url')) {
            return redirect(session('paymentTimes_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.payment-times.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyPaymentTimeRequest $request, PaymentTime $paymentTime): RedirectResponse
    {
        
        $paymentTime->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyPaymentTimeRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    PaymentTime::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                PaymentTime::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
