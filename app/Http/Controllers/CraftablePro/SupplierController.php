<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\Supplier\BulkDestroySupplierRequest;
use App\Http\Requests\CraftablePro\Supplier\CreateSupplierRequest;
use App\Http\Requests\CraftablePro\Supplier\DestroySupplierRequest;
use App\Http\Requests\CraftablePro\Supplier\EditSupplierRequest;
use App\Http\Requests\CraftablePro\Supplier\IndexSupplierRequest;
use App\Http\Requests\CraftablePro\Supplier\StoreSupplierRequest;
use App\Http\Requests\CraftablePro\Supplier\UpdateSupplierRequest;
use App\Models\Supplier;
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

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexSupplierRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $suppliersQuery = QueryBuilder::for(Supplier::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'store_id', 'city_id', 'created_by', 'code', 'phone', 'first_name', 'last_name', 'is_company', 'company_name', 'tax_number', 'is_tax_exempted', 'billing_address', 'postal_code', 'email', 'is_active', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'store_id', 'city_id', 'created_by', 'code', 'phone', 'first_name', 'last_name', 'is_company', 'company_name', 'tax_number', 'is_tax_exempted', 'billing_address', 'postal_code', 'email', 'is_active', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($suppliersQuery->select(['id'])->pluck('id'));
        }

        $suppliers = $suppliersQuery
            ->with([])
            ->select('id', 'store_id', 'city_id', 'created_by', 'code', 'phone', 'first_name', 'last_name', 'is_company', 'company_name', 'tax_number', 'is_tax_exempted', 'billing_address', 'postal_code', 'email', 'is_active', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('suppliers_url', $request->fullUrl());

        return Inertia::render('Supplier/Index', [
            'suppliers' => $suppliers,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateSupplierRequest $request): Response
    {
        return Inertia::render('Supplier/Create', [
            'cities' => \App\Models\City::orderBy('name')->get(['id', 'name']),
            'craftable_pro_users' => \Brackets\CraftablePro\Models\CraftableProUser::orderBy('email')->get(['id', 'email']),
            'stores' => \App\Models\Store::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreSupplierRequest $request): RedirectResponse
    {
        $supplier = Supplier::create($request->validated());
        
        return redirect()->route('craftable-pro.suppliers.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditSupplierRequest $request, Supplier $supplier): Response
    {
        
        return Inertia::render('Supplier/Edit', [
            'supplier' => $supplier,
            'cities' => \App\Models\City::orderBy('name')->get(['id', 'name']),
            'craftable_pro_users' => \Brackets\CraftablePro\Models\CraftableProUser::orderBy('email')->get(['id', 'email']),
            'stores' => \App\Models\Store::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        $supplier->update($request->validated());
        
        if (session('suppliers_url')) {
            return redirect(session('suppliers_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.suppliers.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroySupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        
        $supplier->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroySupplierRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    Supplier::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                Supplier::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
