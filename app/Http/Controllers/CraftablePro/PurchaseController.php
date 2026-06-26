<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\Purchase\BulkDestroyPurchaseRequest;
use App\Http\Requests\CraftablePro\Purchase\CreatePurchaseRequest;
use App\Http\Requests\CraftablePro\Purchase\DestroyPurchaseRequest;
use App\Http\Requests\CraftablePro\Purchase\EditPurchaseRequest;
use App\Http\Requests\CraftablePro\Purchase\IndexPurchaseRequest;
use App\Http\Requests\CraftablePro\Purchase\StorePurchaseRequest;
use App\Http\Requests\CraftablePro\Purchase\UpdatePurchaseRequest;
use App\Models\Purchase;
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

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexPurchaseRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $purchasesQuery = QueryBuilder::for(Purchase::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'store_id', 'supplier_id', 'description', 'is_paid', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'store_id', 'supplier_id', 'entry_stock_time', 'description', 'is_paid', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($purchasesQuery->select(['id'])->pluck('id'));
        }

        $purchases = $purchasesQuery
            ->with([])
            ->select('id', 'store_id', 'supplier_id', 'entry_stock_time', 'description', 'is_paid', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('purchases_url', $request->fullUrl());

        return Inertia::render('Purchase/Index', [
            'purchases' => $purchases,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreatePurchaseRequest $request): Response
    {
        return Inertia::render('Purchase/Create', [
            
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StorePurchaseRequest $request): RedirectResponse
    {
        $purchase = Purchase::create($request->validated());
        
        return redirect()->route('craftable-pro.purchases.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditPurchaseRequest $request, Purchase $purchase): Response
    {
        
        return Inertia::render('Purchase/Edit', [
            'purchase' => $purchase,
            
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePurchaseRequest $request, Purchase $purchase): RedirectResponse
    {
        $purchase->update($request->validated());
        
        if (session('purchases_url')) {
            return redirect(session('purchases_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.purchases.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyPurchaseRequest $request, Purchase $purchase): RedirectResponse
    {
        
        $purchase->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyPurchaseRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    Purchase::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                Purchase::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
