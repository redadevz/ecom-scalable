<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\Discount\BulkDestroyDiscountRequest;
use App\Http\Requests\CraftablePro\Discount\CreateDiscountRequest;
use App\Http\Requests\CraftablePro\Discount\DestroyDiscountRequest;
use App\Http\Requests\CraftablePro\Discount\EditDiscountRequest;
use App\Http\Requests\CraftablePro\Discount\IndexDiscountRequest;
use App\Http\Requests\CraftablePro\Discount\StoreDiscountRequest;
use App\Http\Requests\CraftablePro\Discount\UpdateDiscountRequest;
use App\Models\Discount;
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

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexDiscountRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $discountsQuery = QueryBuilder::for(Discount::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'discount_type_id', 'item_category_id', 'item_id', 'description', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'discount_type_id', 'item_category_id', 'item_id', 'description', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($discountsQuery->select(['id'])->pluck('id'));
        }

        $discounts = $discountsQuery
            ->with([
                'discountType:id,name',
                'itemCategory:id,name',
                'item:id,name',
            ])
            ->select('id', 'discount_type_id', 'item_category_id', 'item_id', 'description', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('discounts_url', $request->fullUrl());

        return Inertia::render('Discount/Index', [
            'discounts' => $discounts,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateDiscountRequest $request): Response
    {
        return Inertia::render('Discount/Create', [
            'discount_types' => \App\Models\DiscountType::orderBy('name')->get(['id', 'name']),
            'item_categories' => \App\Models\ItemCategory::orderBy('name')->get(['id', 'name']),
            'items' => \App\Models\Item::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreDiscountRequest $request): RedirectResponse
    {
        $discount = Discount::create($request->validated());
        
        return redirect()->route('craftable-pro.discounts.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditDiscountRequest $request, Discount $discount): Response
    {
        
        return Inertia::render('Discount/Edit', [
            'discount' => $discount,
            'discount_types' => \App\Models\DiscountType::orderBy('name')->get(['id', 'name']),
            'item_categories' => \App\Models\ItemCategory::orderBy('name')->get(['id', 'name']),
            'items' => \App\Models\Item::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDiscountRequest $request, Discount $discount): RedirectResponse
    {
        $discount->update($request->validated());
        
        if (session('discounts_url')) {
            return redirect(session('discounts_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.discounts.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyDiscountRequest $request, Discount $discount): RedirectResponse
    {
        
        $discount->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyDiscountRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    Discount::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                Discount::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
