<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\ItemTaxType\BulkDestroyItemTaxTypeRequest;
use App\Http\Requests\CraftablePro\ItemTaxType\CreateItemTaxTypeRequest;
use App\Http\Requests\CraftablePro\ItemTaxType\DestroyItemTaxTypeRequest;
use App\Http\Requests\CraftablePro\ItemTaxType\EditItemTaxTypeRequest;
use App\Http\Requests\CraftablePro\ItemTaxType\IndexItemTaxTypeRequest;
use App\Http\Requests\CraftablePro\ItemTaxType\StoreItemTaxTypeRequest;
use App\Http\Requests\CraftablePro\ItemTaxType\UpdateItemTaxTypeRequest;
use App\Models\ItemTaxType;
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

class ItemTaxTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexItemTaxTypeRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $itemTaxTypesQuery = QueryBuilder::for(ItemTaxType::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'item_id', 'tax_type_id', 'description'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'item_id', 'tax_type_id', 'start_time', 'end_time', 'description', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($itemTaxTypesQuery->select(['id'])->pluck('id'));
        }

        $itemTaxTypes = $itemTaxTypesQuery
            ->with(['item:id,name', 'taxType:id,name'])
            ->select('id', 'item_id', 'tax_type_id', 'start_time', 'end_time', 'description', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('itemTaxTypes_url', $request->fullUrl());

        return Inertia::render('ItemTaxType/Index', [
            'itemTaxTypes' => $itemTaxTypes,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateItemTaxTypeRequest $request): Response
    {
        return Inertia::render('ItemTaxType/Create', [
            'items' => \App\Models\Item::orderBy('name')->get(['id', 'name']),
            'tax_types' => \App\Models\TaxType::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreItemTaxTypeRequest $request): RedirectResponse
    {
        $itemTaxType = ItemTaxType::create($request->validated());
        
        return redirect()->route('craftable-pro.item-tax-types.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditItemTaxTypeRequest $request, ItemTaxType $itemTaxType): Response
    {
        
        return Inertia::render('ItemTaxType/Edit', [
            'itemTaxType' => $itemTaxType,
            'items' => \App\Models\Item::orderBy('name')->get(['id', 'name']),
            'tax_types' => \App\Models\TaxType::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemTaxTypeRequest $request, ItemTaxType $itemTaxType): RedirectResponse
    {
        $itemTaxType->update($request->validated());
        
        if (session('itemTaxTypes_url')) {
            return redirect(session('itemTaxTypes_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.item-tax-types.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyItemTaxTypeRequest $request, ItemTaxType $itemTaxType): RedirectResponse
    {
        
        $itemTaxType->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyItemTaxTypeRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    ItemTaxType::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                ItemTaxType::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
