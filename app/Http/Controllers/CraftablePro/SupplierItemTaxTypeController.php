<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\SupplierItemTaxType\BulkDestroySupplierItemTaxTypeRequest;
use App\Http\Requests\CraftablePro\SupplierItemTaxType\CreateSupplierItemTaxTypeRequest;
use App\Http\Requests\CraftablePro\SupplierItemTaxType\DestroySupplierItemTaxTypeRequest;
use App\Http\Requests\CraftablePro\SupplierItemTaxType\EditSupplierItemTaxTypeRequest;
use App\Http\Requests\CraftablePro\SupplierItemTaxType\IndexSupplierItemTaxTypeRequest;
use App\Http\Requests\CraftablePro\SupplierItemTaxType\StoreSupplierItemTaxTypeRequest;
use App\Http\Requests\CraftablePro\SupplierItemTaxType\UpdateSupplierItemTaxTypeRequest;
use App\Models\SupplierItemTaxType;
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

class SupplierItemTaxTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexSupplierItemTaxTypeRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $supplierItemTaxTypesQuery = QueryBuilder::for(SupplierItemTaxType::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'item_id', 'supplier_tax_type_id', 'description'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'item_id', 'supplier_tax_type_id', 'start_time', 'end_time', 'description', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($supplierItemTaxTypesQuery->select(['id'])->pluck('id'));
        }

        $supplierItemTaxTypes = $supplierItemTaxTypesQuery
            ->with([
                'item:id,name',
                'supplierTaxType:id,name,supplier_id',
                'supplierTaxType.supplier:id,company_name,first_name,last_name',
            ])
            ->select('id', 'item_id', 'supplier_tax_type_id', 'start_time', 'end_time', 'description', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('supplierItemTaxTypes_url', $request->fullUrl());

        return Inertia::render('SupplierItemTaxType/Index', [
            'supplierItemTaxTypes' => $supplierItemTaxTypes,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateSupplierItemTaxTypeRequest $request): Response
    {
        return Inertia::render('SupplierItemTaxType/Create', [
            'items' => \App\Models\Item::orderBy('name')->get(['id', 'name']),
            'supplier_tax_types' => \App\Models\SupplierTaxType::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreSupplierItemTaxTypeRequest $request): RedirectResponse
    {
        $supplierItemTaxType = SupplierItemTaxType::create($request->validated());
        
        return redirect()->route('craftable-pro.supplier-item-tax-types.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditSupplierItemTaxTypeRequest $request, SupplierItemTaxType $supplierItemTaxType): Response
    {
        
        return Inertia::render('SupplierItemTaxType/Edit', [
            'supplierItemTaxType' => $supplierItemTaxType,
            'items' => \App\Models\Item::orderBy('name')->get(['id', 'name']),
            'supplier_tax_types' => \App\Models\SupplierTaxType::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierItemTaxTypeRequest $request, SupplierItemTaxType $supplierItemTaxType): RedirectResponse
    {
        $supplierItemTaxType->update($request->validated());
        
        if (session('supplierItemTaxTypes_url')) {
            return redirect(session('supplierItemTaxTypes_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.supplier-item-tax-types.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroySupplierItemTaxTypeRequest $request, SupplierItemTaxType $supplierItemTaxType): RedirectResponse
    {
        
        $supplierItemTaxType->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroySupplierItemTaxTypeRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    SupplierItemTaxType::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                SupplierItemTaxType::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
