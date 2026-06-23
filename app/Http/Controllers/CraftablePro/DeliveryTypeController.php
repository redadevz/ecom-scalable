<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\DeliveryType\BulkDestroyDeliveryTypeRequest;
use App\Http\Requests\CraftablePro\DeliveryType\CreateDeliveryTypeRequest;
use App\Http\Requests\CraftablePro\DeliveryType\DestroyDeliveryTypeRequest;
use App\Http\Requests\CraftablePro\DeliveryType\EditDeliveryTypeRequest;
use App\Http\Requests\CraftablePro\DeliveryType\IndexDeliveryTypeRequest;
use App\Http\Requests\CraftablePro\DeliveryType\StoreDeliveryTypeRequest;
use App\Http\Requests\CraftablePro\DeliveryType\UpdateDeliveryTypeRequest;
use App\Models\DeliveryType;
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

class DeliveryTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexDeliveryTypeRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $deliveryTypesQuery = QueryBuilder::for(DeliveryType::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'name', 'description', 'is_active'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'name', 'description', 'is_active', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($deliveryTypesQuery->select(['id'])->pluck('id'));
        }

        $deliveryTypes = $deliveryTypesQuery
            ->with([])
            ->select('id', 'name', 'description', 'is_active', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('deliveryTypes_url', $request->fullUrl());

        return Inertia::render('DeliveryType/Index', [
            'deliveryTypes' => $deliveryTypes,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateDeliveryTypeRequest $request): Response
    {
        return Inertia::render('DeliveryType/Create', [
            
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreDeliveryTypeRequest $request): RedirectResponse
    {
        $deliveryType = DeliveryType::create($request->validated());
        
        return redirect()->route('craftable-pro.delivery-types.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditDeliveryTypeRequest $request, DeliveryType $deliveryType): Response
    {
        
        return Inertia::render('DeliveryType/Edit', [
            'deliveryType' => $deliveryType,
            
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeliveryTypeRequest $request, DeliveryType $deliveryType): RedirectResponse
    {
        $deliveryType->update($request->validated());
        
        if (session('deliveryTypes_url')) {
            return redirect(session('deliveryTypes_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.delivery-types.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyDeliveryTypeRequest $request, DeliveryType $deliveryType): RedirectResponse
    {
        
        $deliveryType->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyDeliveryTypeRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    DeliveryType::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                DeliveryType::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
