<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\TaxType\BulkDestroyTaxTypeRequest;
use App\Http\Requests\CraftablePro\TaxType\CreateTaxTypeRequest;
use App\Http\Requests\CraftablePro\TaxType\DestroyTaxTypeRequest;
use App\Http\Requests\CraftablePro\TaxType\EditTaxTypeRequest;
use App\Http\Requests\CraftablePro\TaxType\IndexTaxTypeRequest;
use App\Http\Requests\CraftablePro\TaxType\StoreTaxTypeRequest;
use App\Http\Requests\CraftablePro\TaxType\UpdateTaxTypeRequest;
use App\Models\TaxType;
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

class TaxTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexTaxTypeRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $taxTypesQuery = QueryBuilder::for(TaxType::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'store_id', 'name', 'code', 'description', 'is_percentage', 'value', 'is_active', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'store_id', 'name', 'code', 'description', 'is_percentage', 'value', 'start_time', 'end_time', 'is_active', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($taxTypesQuery->select(['id'])->pluck('id'));
        }

        $taxTypes = $taxTypesQuery
            ->with([])
            ->select('id', 'store_id', 'name', 'code', 'description', 'is_percentage', 'value', 'start_time', 'end_time', 'is_active', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('taxTypes_url', $request->fullUrl());

        return Inertia::render('TaxType/Index', [
            'taxTypes' => $taxTypes,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateTaxTypeRequest $request): Response
    {
        return Inertia::render('TaxType/Create', [
            'stores' => \App\Models\Store::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreTaxTypeRequest $request): RedirectResponse
    {
        $taxType = TaxType::create($request->validated());
        
        return redirect()->route('craftable-pro.tax-types.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditTaxTypeRequest $request, TaxType $taxType): Response
    {
        
        return Inertia::render('TaxType/Edit', [
            'taxType' => $taxType,
            'stores' => \App\Models\Store::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaxTypeRequest $request, TaxType $taxType): RedirectResponse
    {
        $taxType->update($request->validated());
        
        if (session('taxTypes_url')) {
            return redirect(session('taxTypes_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.tax-types.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyTaxTypeRequest $request, TaxType $taxType): RedirectResponse
    {
        
        $taxType->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyTaxTypeRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    TaxType::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                TaxType::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
