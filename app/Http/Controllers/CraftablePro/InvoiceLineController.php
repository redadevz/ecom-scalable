<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\InvoiceLine\BulkDestroyInvoiceLineRequest;
use App\Http\Requests\CraftablePro\InvoiceLine\CreateInvoiceLineRequest;
use App\Http\Requests\CraftablePro\InvoiceLine\DestroyInvoiceLineRequest;
use App\Http\Requests\CraftablePro\InvoiceLine\EditInvoiceLineRequest;
use App\Http\Requests\CraftablePro\InvoiceLine\IndexInvoiceLineRequest;
use App\Http\Requests\CraftablePro\InvoiceLine\StoreInvoiceLineRequest;
use App\Http\Requests\CraftablePro\InvoiceLine\UpdateInvoiceLineRequest;
use App\Models\InvoiceLine;
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

class InvoiceLineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexInvoiceLineRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $invoiceLinesQuery = QueryBuilder::for(InvoiceLine::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'invoice_id', 'order_line_id', 'line_no', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'invoice_id', 'order_line_id', 'line_no', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($invoiceLinesQuery->select(['id'])->pluck('id'));
        }

        $invoiceLines = $invoiceLinesQuery
            ->with([])
            ->select('id', 'invoice_id', 'order_line_id', 'line_no', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('invoiceLines_url', $request->fullUrl());

        return Inertia::render('InvoiceLine/Index', [
            'invoiceLines' => $invoiceLines,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateInvoiceLineRequest $request): Response
    {
        return Inertia::render('InvoiceLine/Create', [
            'invoices' => \App\Models\Invoice::orderBy('invoice_no')->get(['id', 'invoice_no']),
            'order_lines' => \App\Models\OrderLine::orderBy('line_no')->get(['id', 'line_no']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreInvoiceLineRequest $request): RedirectResponse
    {
        $invoiceLine = InvoiceLine::create($request->validated());
        
        return redirect()->route('craftable-pro.invoice-lines.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditInvoiceLineRequest $request, InvoiceLine $invoiceLine): Response
    {
        
        return Inertia::render('InvoiceLine/Edit', [
            'invoiceLine' => $invoiceLine,
            'invoices' => \App\Models\Invoice::orderBy('invoice_no')->get(['id', 'invoice_no']),
            'order_lines' => \App\Models\OrderLine::orderBy('line_no')->get(['id', 'line_no']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceLineRequest $request, InvoiceLine $invoiceLine): RedirectResponse
    {
        $invoiceLine->update($request->validated());
        
        if (session('invoiceLines_url')) {
            return redirect(session('invoiceLines_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.invoice-lines.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyInvoiceLineRequest $request, InvoiceLine $invoiceLine): RedirectResponse
    {
        
        $invoiceLine->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyInvoiceLineRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    InvoiceLine::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                InvoiceLine::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
