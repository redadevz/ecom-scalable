<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\LossAndDamageItem\BulkDestroyLossAndDamageItemRequest;
use App\Http\Requests\CraftablePro\LossAndDamageItem\CreateLossAndDamageItemRequest;
use App\Http\Requests\CraftablePro\LossAndDamageItem\DestroyLossAndDamageItemRequest;
use App\Http\Requests\CraftablePro\LossAndDamageItem\EditLossAndDamageItemRequest;
use App\Http\Requests\CraftablePro\LossAndDamageItem\IndexLossAndDamageItemRequest;
use App\Http\Requests\CraftablePro\LossAndDamageItem\StoreLossAndDamageItemRequest;
use App\Http\Requests\CraftablePro\LossAndDamageItem\UpdateLossAndDamageItemRequest;
use App\Models\LossAndDamageItem;
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

class LossAndDamageItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexLossAndDamageItemRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $lossAndDamageItemsQuery = QueryBuilder::for(LossAndDamageItem::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'loss_and_damage_id', 'item_id', 'quantity', 'description', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'loss_and_damage_id', 'item_id', 'quantity', 'description', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($lossAndDamageItemsQuery->select(['id'])->pluck('id'));
        }

        $lossAndDamageItems = $lossAndDamageItemsQuery
            ->with([])
            ->select('id', 'loss_and_damage_id', 'item_id', 'quantity', 'description', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('lossAndDamageItems_url', $request->fullUrl());

        return Inertia::render('LossAndDamageItem/Index', [
            'lossAndDamageItems' => $lossAndDamageItems,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateLossAndDamageItemRequest $request): Response
    {
        return Inertia::render('LossAndDamageItem/Create', [
            'items' => \App\Models\Item::orderBy('name')->get(['id', 'name']),
            'loss_and_damages' => \App\Models\LossAndDamage::orderBy('id')->get(['id', 'description'])->map(fn ($m) => ['id' => $m->id, 'description' => '#' . $m->id . ' ' . ($m->description ?? '')]),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreLossAndDamageItemRequest $request): RedirectResponse
    {
        $lossAndDamageItem = LossAndDamageItem::create($request->validated());
        
        return redirect()->route('craftable-pro.loss-and-damage-items.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditLossAndDamageItemRequest $request, LossAndDamageItem $lossAndDamageItem): Response
    {
        
        return Inertia::render('LossAndDamageItem/Edit', [
            'lossAndDamageItem' => $lossAndDamageItem,
            'items' => \App\Models\Item::orderBy('name')->get(['id', 'name']),
            'loss_and_damages' => \App\Models\LossAndDamage::orderBy('id')->get(['id', 'description'])->map(fn ($m) => ['id' => $m->id, 'description' => '#' . $m->id . ' ' . ($m->description ?? '')]),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLossAndDamageItemRequest $request, LossAndDamageItem $lossAndDamageItem): RedirectResponse
    {
        $lossAndDamageItem->update($request->validated());
        
        if (session('lossAndDamageItems_url')) {
            return redirect(session('lossAndDamageItems_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.loss-and-damage-items.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyLossAndDamageItemRequest $request, LossAndDamageItem $lossAndDamageItem): RedirectResponse
    {
        
        $lossAndDamageItem->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyLossAndDamageItemRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    LossAndDamageItem::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                LossAndDamageItem::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
