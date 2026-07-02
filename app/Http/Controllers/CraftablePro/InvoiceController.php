<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\Invoice\BulkDestroyInvoiceRequest;
use App\Http\Requests\CraftablePro\Invoice\CreateInvoiceRequest;
use App\Http\Requests\CraftablePro\Invoice\DestroyInvoiceRequest;
use App\Http\Requests\CraftablePro\Invoice\EditInvoiceRequest;
use App\Http\Requests\CraftablePro\Invoice\IndexInvoiceRequest;
use App\Http\Requests\CraftablePro\Invoice\StoreInvoiceRequest;
use App\Http\Requests\CraftablePro\Invoice\UpdateInvoiceRequest;
use App\Models\Invoice;
use App\Services\PaymentService;
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

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexInvoiceRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $invoicesQuery = QueryBuilder::for(Invoice::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'order_id', 'invoice_no', 'is_paid', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'order_id', 'invoice_no', 'is_paid', 'payment_time', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($invoicesQuery->select(['id'])->pluck('id'));
        }

        $invoices = $invoicesQuery
            ->with(['order:id,order_no'])
            ->select('id', 'order_id', 'invoice_no', 'is_paid', 'payment_time', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('invoices_url', $request->fullUrl());

        return Inertia::render('Invoice/Index', [
            'invoices' => $invoices,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateInvoiceRequest $request): Response
    {
        return Inertia::render('Invoice/Create', [
            'order_headers' => \App\Models\OrderHeader::orderBy('order_no')->get(['id', 'order_no']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $invoice = Invoice::create($request->validated());
        
        return redirect()->route('craftable-pro.invoices.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditInvoiceRequest $request, Invoice $invoice): Response
    {
        
        return Inertia::render('Invoice/Edit', [
            'invoice' => $invoice,
            'order_headers' => \App\Models\OrderHeader::orderBy('order_no')->get(['id', 'order_no']),
            'order_headers' => \App\Models\OrderHeader::orderBy('order_no')->get(['id', 'order_no']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        $invoice->update($request->validated());
        
        if (session('invoices_url')) {
            return redirect(session('invoices_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.invoices.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        
        $invoice->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyInvoiceRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    Invoice::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                Invoice::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    public function pay(Invoice $invoice, PaymentService $payments){
        try{
            $payments->settle($invoice);
            return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }catch(\RuntimeException $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
}
