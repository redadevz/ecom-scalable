<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\Region\BulkDestroyRegionRequest;
use App\Http\Requests\CraftablePro\Region\CreateRegionRequest;
use App\Http\Requests\CraftablePro\Region\DestroyRegionRequest;
use App\Http\Requests\CraftablePro\Region\EditRegionRequest;
use App\Http\Requests\CraftablePro\Region\IndexRegionRequest;
use App\Http\Requests\CraftablePro\Region\StoreRegionRequest;
use App\Http\Requests\CraftablePro\Region\UpdateRegionRequest;
use App\Models\Region;
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

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRegionRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $regionsQuery = QueryBuilder::for(Region::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'name', 'country_id'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'name', 'country_id', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($regionsQuery->select(['id'])->pluck('id'));
        }

        $regions = $regionsQuery
            ->with([])
            ->select('id', 'name', 'country_id', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('regions_url', $request->fullUrl());

        return Inertia::render('Region/Index', [
            'regions' => $regions,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateRegionRequest $request): Response
    {
        return Inertia::render('Region/Create', [
            'countries' => \App\Models\Country::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreRegionRequest $request): RedirectResponse
    {
        $region = Region::create($request->validated());
        
        return redirect()->route('craftable-pro.regions.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditRegionRequest $request, Region $region): Response
    {
        
        return Inertia::render('Region/Edit', [
            'region' => $region,
            'countries' => \App\Models\Country::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRegionRequest $request, Region $region): RedirectResponse
    {
        $region->update($request->validated());
        
        if (session('regions_url')) {
            return redirect(session('regions_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.regions.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyRegionRequest $request, Region $region): RedirectResponse
    {
        
        $region->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyRegionRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    Region::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                Region::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
