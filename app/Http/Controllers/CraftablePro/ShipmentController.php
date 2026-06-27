<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\Shipment\BulkDestroyShipmentRequest;
use App\Http\Requests\CraftablePro\Shipment\CreateShipmentRequest;
use App\Http\Requests\CraftablePro\Shipment\DestroyShipmentRequest;
use App\Http\Requests\CraftablePro\Shipment\EditShipmentRequest;
use App\Http\Requests\CraftablePro\Shipment\IndexShipmentRequest;
use App\Http\Requests\CraftablePro\Shipment\StoreShipmentRequest;
use App\Http\Requests\CraftablePro\Shipment\UpdateShipmentRequest;
use App\Models\Shipment;
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

class ShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexShipmentRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $shipmentsQuery = QueryBuilder::for(Shipment::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'store_id', 'order_id', 'shipment_city_id', 'picked_up_by', 'shipment_address', 'gps_location', 'postal_code', 'shipment_notes', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'store_id', 'order_id', 'shipment_city_id', 'picked_up_by', 'shipment_address', 'gps_location', 'postal_code', 'shipment_notes', 'picked_up_time', 'shipped_time', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($shipmentsQuery->select(['id'])->pluck('id'));
        }

        $shipments = $shipmentsQuery
            ->with([])
            ->select('id', 'store_id', 'order_id', 'shipment_city_id', 'picked_up_by', 'shipment_address', 'gps_location', 'postal_code', 'shipment_notes', 'picked_up_time', 'shipped_time', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('shipments_url', $request->fullUrl());

        return Inertia::render('Shipment/Index', [
            'shipments' => $shipments,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateShipmentRequest $request): Response
    {
        return Inertia::render('Shipment/Create', [
            'order_headers' => \App\Models\OrderHeader::orderBy('order_no')->get(['id', 'order_no']),
            'craftable_pro_users' => \Brackets\CraftablePro\Models\CraftableProUser::orderBy('email')->get(['id', 'email']),
            'cities' => \App\Models\City::orderBy('name')->get(['id', 'name']),
            'stores' => \App\Models\Store::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreShipmentRequest $request): RedirectResponse
    {
        $shipment = Shipment::create($request->validated());
        
        return redirect()->route('craftable-pro.shipments.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditShipmentRequest $request, Shipment $shipment): Response
    {
        
        return Inertia::render('Shipment/Edit', [
            'shipment' => $shipment,
            'order_headers' => \App\Models\OrderHeader::orderBy('order_no')->get(['id', 'order_no']),
            'craftable_pro_users' => \Brackets\CraftablePro\Models\CraftableProUser::orderBy('email')->get(['id', 'email']),
            'cities' => \App\Models\City::orderBy('name')->get(['id', 'name']),
            'stores' => \App\Models\Store::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShipmentRequest $request, Shipment $shipment): RedirectResponse
    {
        $shipment->update($request->validated());
        
        if (session('shipments_url')) {
            return redirect(session('shipments_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.shipments.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyShipmentRequest $request, Shipment $shipment): RedirectResponse
    {
        
        $shipment->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyShipmentRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    Shipment::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                Shipment::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
