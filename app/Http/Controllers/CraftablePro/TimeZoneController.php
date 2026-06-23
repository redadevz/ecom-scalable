<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\TimeZone\BulkDestroyTimeZoneRequest;
use App\Http\Requests\CraftablePro\TimeZone\CreateTimeZoneRequest;
use App\Http\Requests\CraftablePro\TimeZone\DestroyTimeZoneRequest;
use App\Http\Requests\CraftablePro\TimeZone\EditTimeZoneRequest;
use App\Http\Requests\CraftablePro\TimeZone\IndexTimeZoneRequest;
use App\Http\Requests\CraftablePro\TimeZone\StoreTimeZoneRequest;
use App\Http\Requests\CraftablePro\TimeZone\UpdateTimeZoneRequest;
use App\Models\TimeZone;
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

class TimeZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexTimeZoneRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $timeZonesQuery = QueryBuilder::for(TimeZone::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'name', 'description'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'name', 'description', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($timeZonesQuery->select(['id'])->pluck('id'));
        }

        $timeZones = $timeZonesQuery
            ->with([])
            ->select('id', 'name', 'description', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('timeZones_url', $request->fullUrl());

        return Inertia::render('TimeZone/Index', [
            'timeZones' => $timeZones,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateTimeZoneRequest $request): Response
    {
        return Inertia::render('TimeZone/Create', [
            
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreTimeZoneRequest $request): RedirectResponse
    {
        $timeZone = TimeZone::create($request->validated());
        
        return redirect()->route('craftable-pro.time-zones.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditTimeZoneRequest $request, TimeZone $timeZone): Response
    {
        
        return Inertia::render('TimeZone/Edit', [
            'timeZone' => $timeZone,
            
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTimeZoneRequest $request, TimeZone $timeZone): RedirectResponse
    {
        $timeZone->update($request->validated());
        
        if (session('timeZones_url')) {
            return redirect(session('timeZones_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.time-zones.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyTimeZoneRequest $request, TimeZone $timeZone): RedirectResponse
    {
        
        $timeZone->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyTimeZoneRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    TimeZone::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                TimeZone::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
