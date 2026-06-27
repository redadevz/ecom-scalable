<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\SupplierTaxType\BulkDestroySupplierTaxTypeRequest;
use App\Http\Requests\CraftablePro\SupplierTaxType\CreateSupplierTaxTypeRequest;
use App\Http\Requests\CraftablePro\SupplierTaxType\DestroySupplierTaxTypeRequest;
use App\Http\Requests\CraftablePro\SupplierTaxType\EditSupplierTaxTypeRequest;
use App\Http\Requests\CraftablePro\SupplierTaxType\IndexSupplierTaxTypeRequest;
use App\Http\Requests\CraftablePro\SupplierTaxType\StoreSupplierTaxTypeRequest;
use App\Http\Requests\CraftablePro\SupplierTaxType\UpdateSupplierTaxTypeRequest;
use App\Models\SupplierTaxType;
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

class SupplierTaxTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexSupplierTaxTypeRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $supplierTaxTypesQuery = QueryBuilder::for(SupplierTaxType::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'supplier_id', 'name', 'code', 'description', 'is_percentage', 'value', 'is_active', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'supplier_id', 'name', 'code', 'description', 'is_percentage', 'value', 'start_time', 'end_time', 'is_active', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($supplierTaxTypesQuery->select(['id'])->pluck('id'));
        }

        $supplierTaxTypes = $supplierTaxTypesQuery
            ->with([])
            ->select('id', 'supplier_id', 'name', 'code', 'description', 'is_percentage', 'value', 'start_time', 'end_time', 'is_active', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('supplierTaxTypes_url', $request->fullUrl());

        return Inertia::render('SupplierTaxType/Index', [
            'supplierTaxTypes' => $supplierTaxTypes,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateSupplierTaxTypeRequest $request): Response
    {
        return Inertia::render('SupplierTaxType/Create', [
            'suppliers' => \App\Models\Supplier::orderBy('code')->get(['id', 'code']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreSupplierTaxTypeRequest $request): RedirectResponse
    {
        $supplierTaxType = SupplierTaxType::create($request->validated());
        
        return redirect()->route('craftable-pro.supplier-tax-types.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditSupplierTaxTypeRequest $request, SupplierTaxType $supplierTaxType): Response
    {
        
        return Inertia::render('SupplierTaxType/Edit', [
            'supplierTaxType' => $supplierTaxType,
            'suppliers' => \App\Models\Supplier::orderBy('code')->get(['id', 'code']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierTaxTypeRequest $request, SupplierTaxType $supplierTaxType): RedirectResponse
    {
        $supplierTaxType->update($request->validated());
        
        if (session('supplierTaxTypes_url')) {
            return redirect(session('supplierTaxTypes_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.supplier-tax-types.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroySupplierTaxTypeRequest $request, SupplierTaxType $supplierTaxType): RedirectResponse
    {
        
        $supplierTaxType->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroySupplierTaxTypeRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    SupplierTaxType::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                SupplierTaxType::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
