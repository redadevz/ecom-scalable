<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\MeasureUnit\BulkDestroyMeasureUnitRequest;
use App\Http\Requests\CraftablePro\MeasureUnit\CreateMeasureUnitRequest;
use App\Http\Requests\CraftablePro\MeasureUnit\DestroyMeasureUnitRequest;
use App\Http\Requests\CraftablePro\MeasureUnit\EditMeasureUnitRequest;
use App\Http\Requests\CraftablePro\MeasureUnit\IndexMeasureUnitRequest;
use App\Http\Requests\CraftablePro\MeasureUnit\StoreMeasureUnitRequest;
use App\Http\Requests\CraftablePro\MeasureUnit\UpdateMeasureUnitRequest;
use App\Models\MeasureUnit;
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

class MeasureUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexMeasureUnitRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $measureUnitsQuery = QueryBuilder::for(MeasureUnit::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'name', 'symbol', 'description'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'name', 'symbol', 'description', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($measureUnitsQuery->select(['id'])->pluck('id'));
        }

        $measureUnits = $measureUnitsQuery
            ->with([])
            ->select('id', 'name', 'symbol', 'description', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('measureUnits_url', $request->fullUrl());

        return Inertia::render('MeasureUnit/Index', [
            'measureUnits' => $measureUnits,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateMeasureUnitRequest $request): Response
    {
        return Inertia::render('MeasureUnit/Create', [
            
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreMeasureUnitRequest $request): RedirectResponse
    {
        $measureUnit = MeasureUnit::create($request->validated());
        
        return redirect()->route('craftable-pro.measure-units.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditMeasureUnitRequest $request, MeasureUnit $measureUnit): Response
    {
        
        return Inertia::render('MeasureUnit/Edit', [
            'measureUnit' => $measureUnit,
            
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMeasureUnitRequest $request, MeasureUnit $measureUnit): RedirectResponse
    {
        $measureUnit->update($request->validated());
        
        if (session('measureUnits_url')) {
            return redirect(session('measureUnits_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.measure-units.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyMeasureUnitRequest $request, MeasureUnit $measureUnit): RedirectResponse
    {
        
        $measureUnit->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyMeasureUnitRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    MeasureUnit::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                MeasureUnit::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
