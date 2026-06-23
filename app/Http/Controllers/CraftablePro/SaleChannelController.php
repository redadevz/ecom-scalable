<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\SaleChannel\BulkDestroySaleChannelRequest;
use App\Http\Requests\CraftablePro\SaleChannel\CreateSaleChannelRequest;
use App\Http\Requests\CraftablePro\SaleChannel\DestroySaleChannelRequest;
use App\Http\Requests\CraftablePro\SaleChannel\EditSaleChannelRequest;
use App\Http\Requests\CraftablePro\SaleChannel\IndexSaleChannelRequest;
use App\Http\Requests\CraftablePro\SaleChannel\StoreSaleChannelRequest;
use App\Http\Requests\CraftablePro\SaleChannel\UpdateSaleChannelRequest;
use App\Models\SaleChannel;
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

class SaleChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexSaleChannelRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $saleChannelsQuery = QueryBuilder::for(SaleChannel::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'name', 'description', 'is_active'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'name', 'description', 'is_active', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($saleChannelsQuery->select(['id'])->pluck('id'));
        }

        $saleChannels = $saleChannelsQuery
            ->with([])
            ->select('id', 'name', 'description', 'is_active', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('saleChannels_url', $request->fullUrl());

        return Inertia::render('SaleChannel/Index', [
            'saleChannels' => $saleChannels,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateSaleChannelRequest $request): Response
    {
        return Inertia::render('SaleChannel/Create', [
            
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreSaleChannelRequest $request): RedirectResponse
    {
        $saleChannel = SaleChannel::create($request->validated());
        
        return redirect()->route('craftable-pro.sale-channels.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditSaleChannelRequest $request, SaleChannel $saleChannel): Response
    {
        
        return Inertia::render('SaleChannel/Edit', [
            'saleChannel' => $saleChannel,
            
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSaleChannelRequest $request, SaleChannel $saleChannel): RedirectResponse
    {
        $saleChannel->update($request->validated());
        
        if (session('saleChannels_url')) {
            return redirect(session('saleChannels_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.sale-channels.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroySaleChannelRequest $request, SaleChannel $saleChannel): RedirectResponse
    {
        
        $saleChannel->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroySaleChannelRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    SaleChannel::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                SaleChannel::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
