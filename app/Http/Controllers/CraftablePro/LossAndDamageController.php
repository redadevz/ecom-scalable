<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\LossAndDamage\BulkDestroyLossAndDamageRequest;
use App\Http\Requests\CraftablePro\LossAndDamage\CreateLossAndDamageRequest;
use App\Http\Requests\CraftablePro\LossAndDamage\DestroyLossAndDamageRequest;
use App\Http\Requests\CraftablePro\LossAndDamage\EditLossAndDamageRequest;
use App\Http\Requests\CraftablePro\LossAndDamage\IndexLossAndDamageRequest;
use App\Http\Requests\CraftablePro\LossAndDamage\StoreLossAndDamageRequest;
use App\Http\Requests\CraftablePro\LossAndDamage\UpdateLossAndDamageRequest;
use App\Models\LossAndDamage;
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

class LossAndDamageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexLossAndDamageRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $lossAndDamagesQuery = QueryBuilder::for(LossAndDamage::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'store_id', 'description', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'store_id', 'exit_stock_time', 'description', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($lossAndDamagesQuery->select(['id'])->pluck('id'));
        }

        $lossAndDamages = $lossAndDamagesQuery
            ->with([])
            ->select('id', 'store_id', 'exit_stock_time', 'description', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('lossAndDamages_url', $request->fullUrl());

        return Inertia::render('LossAndDamage/Index', [
            'lossAndDamages' => $lossAndDamages,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateLossAndDamageRequest $request): Response
    {
        return Inertia::render('LossAndDamage/Create', [
            'stores' => \App\Models\Store::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreLossAndDamageRequest $request): RedirectResponse
    {
        $lossAndDamage = LossAndDamage::create($request->validated());
        
        return redirect()->route('craftable-pro.loss-and-damages.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditLossAndDamageRequest $request, LossAndDamage $lossAndDamage): Response
    {
        
        return Inertia::render('LossAndDamage/Edit', [
            'lossAndDamage' => $lossAndDamage,
            'stores' => \App\Models\Store::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLossAndDamageRequest $request, LossAndDamage $lossAndDamage): RedirectResponse
    {
        $lossAndDamage->update($request->validated());
        
        if (session('lossAndDamages_url')) {
            return redirect(session('lossAndDamages_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.loss-and-damages.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyLossAndDamageRequest $request, LossAndDamage $lossAndDamage): RedirectResponse
    {
        
        $lossAndDamage->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyLossAndDamageRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    LossAndDamage::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                LossAndDamage::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
