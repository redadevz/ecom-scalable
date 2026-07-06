<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\DiscountSchedule\BulkDestroyDiscountScheduleRequest;
use App\Http\Requests\CraftablePro\DiscountSchedule\CreateDiscountScheduleRequest;
use App\Http\Requests\CraftablePro\DiscountSchedule\DestroyDiscountScheduleRequest;
use App\Http\Requests\CraftablePro\DiscountSchedule\EditDiscountScheduleRequest;
use App\Http\Requests\CraftablePro\DiscountSchedule\IndexDiscountScheduleRequest;
use App\Http\Requests\CraftablePro\DiscountSchedule\StoreDiscountScheduleRequest;
use App\Http\Requests\CraftablePro\DiscountSchedule\UpdateDiscountScheduleRequest;
use App\Models\DiscountSchedule;
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

class DiscountScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexDiscountScheduleRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $discountSchedulesQuery = QueryBuilder::for(DiscountSchedule::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'discount_type_id', 'day_of_week', 'start_time', 'end_time', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'discount_type_id', 'day_of_week', 'start_time', 'end_time', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($discountSchedulesQuery->select(['id'])->pluck('id'));
        }

        $discountSchedules = $discountSchedulesQuery
            ->with(['discountType:id,name'])
            ->select('id', 'discount_type_id', 'day_of_week', 'start_time', 'end_time', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('discountSchedules_url', $request->fullUrl());

        return Inertia::render('DiscountSchedule/Index', [
            'discountSchedules' => $discountSchedules,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateDiscountScheduleRequest $request): Response
    {
        return Inertia::render('DiscountSchedule/Create', [
            'discount_types' => \App\Models\DiscountType::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreDiscountScheduleRequest $request): RedirectResponse
    {
        $discountSchedule = DiscountSchedule::create($request->validated());
        
        return redirect()->route('craftable-pro.discount-schedules.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditDiscountScheduleRequest $request, DiscountSchedule $discountSchedule): Response
    {
        
        return Inertia::render('DiscountSchedule/Edit', [
            'discountSchedule' => $discountSchedule,
            'discount_types' => \App\Models\DiscountType::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDiscountScheduleRequest $request, DiscountSchedule $discountSchedule): RedirectResponse
    {
        $discountSchedule->update($request->validated());
        
        if (session('discountSchedules_url')) {
            return redirect(session('discountSchedules_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.discount-schedules.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyDiscountScheduleRequest $request, DiscountSchedule $discountSchedule): RedirectResponse
    {
        
        $discountSchedule->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyDiscountScheduleRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    DiscountSchedule::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                DiscountSchedule::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
