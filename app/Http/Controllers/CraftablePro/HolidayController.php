<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\Holiday\BulkDestroyHolidayRequest;
use App\Http\Requests\CraftablePro\Holiday\CreateHolidayRequest;
use App\Http\Requests\CraftablePro\Holiday\DestroyHolidayRequest;
use App\Http\Requests\CraftablePro\Holiday\EditHolidayRequest;
use App\Http\Requests\CraftablePro\Holiday\IndexHolidayRequest;
use App\Http\Requests\CraftablePro\Holiday\StoreHolidayRequest;
use App\Http\Requests\CraftablePro\Holiday\UpdateHolidayRequest;
use App\Models\Holiday;
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

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexHolidayRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $holidaysQuery = QueryBuilder::for(Holiday::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'store_id', 'name', 'reason', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'store_id', 'name', 'reason', 'starts_at', 'ends_at', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($holidaysQuery->select(['id'])->pluck('id'));
        }

        $holidays = $holidaysQuery
            ->with([])
            ->select('id', 'store_id', 'name', 'reason', 'starts_at', 'ends_at', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('holidays_url', $request->fullUrl());

        return Inertia::render('Holiday/Index', [
            'holidays' => $holidays,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateHolidayRequest $request): Response
    {
        return Inertia::render('Holiday/Create', [
            
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreHolidayRequest $request): RedirectResponse
    {
        $holiday = Holiday::create($request->validated());
        
        return redirect()->route('craftable-pro.holidays.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditHolidayRequest $request, Holiday $holiday): Response
    {
        
        return Inertia::render('Holiday/Edit', [
            'holiday' => $holiday,
            
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHolidayRequest $request, Holiday $holiday): RedirectResponse
    {
        $holiday->update($request->validated());
        
        if (session('holidays_url')) {
            return redirect(session('holidays_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.holidays.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyHolidayRequest $request, Holiday $holiday): RedirectResponse
    {
        
        $holiday->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyHolidayRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    Holiday::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                Holiday::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
