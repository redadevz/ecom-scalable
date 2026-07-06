<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\DiscountType\BulkDestroyDiscountTypeRequest;
use App\Http\Requests\CraftablePro\DiscountType\CreateDiscountTypeRequest;
use App\Http\Requests\CraftablePro\DiscountType\DestroyDiscountTypeRequest;
use App\Http\Requests\CraftablePro\DiscountType\EditDiscountTypeRequest;
use App\Http\Requests\CraftablePro\DiscountType\IndexDiscountTypeRequest;
use App\Http\Requests\CraftablePro\DiscountType\StoreDiscountTypeRequest;
use App\Http\Requests\CraftablePro\DiscountType\UpdateDiscountTypeRequest;
use App\Models\DiscountType;
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

class DiscountTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexDiscountTypeRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $discountTypesQuery = QueryBuilder::for(DiscountType::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'store_id', 'loyalty_card_type_id', 'name', 'description', 'is_percentage', 'value', 'coupon_code', 'min_order_value', 'min_item_quantity', 'apply_to_all', 'apply_to_next', 'max_discount_value', 'is_active', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'store_id', 'loyalty_card_type_id', 'name', 'description', 'is_percentage', 'value', 'coupon_code', 'min_order_value', 'min_item_quantity', 'apply_to_all', 'apply_to_next', 'max_discount_value', 'start_time', 'end_time', 'is_active', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($discountTypesQuery->select(['id'])->pluck('id'));
        }

        $discountTypes = $discountTypesQuery
            ->with(['store:id,name'])
            ->select('id', 'store_id', 'loyalty_card_type_id', 'name', 'description', 'is_percentage', 'value', 'coupon_code', 'min_order_value', 'min_item_quantity', 'apply_to_all', 'apply_to_next', 'max_discount_value', 'start_time', 'end_time', 'is_active', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('discountTypes_url', $request->fullUrl());

        return Inertia::render('DiscountType/Index', [
            'discountTypes' => $discountTypes,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateDiscountTypeRequest $request): Response
    {
        return Inertia::render('DiscountType/Create', [
            'loyalty_card_types' => \App\Models\LoyaltyCardType::orderBy('name')->get(['id', 'name']),
            'stores' => \App\Models\Store::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreDiscountTypeRequest $request): RedirectResponse
    {
        $discountType = DiscountType::create($request->validated());
        
        return redirect()->route('craftable-pro.discount-types.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditDiscountTypeRequest $request, DiscountType $discountType): Response
    {
        
        return Inertia::render('DiscountType/Edit', [
            'discountType' => $discountType,
            'loyalty_card_types' => \App\Models\LoyaltyCardType::orderBy('name')->get(['id', 'name']),
            'stores' => \App\Models\Store::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDiscountTypeRequest $request, DiscountType $discountType): RedirectResponse
    {
        $discountType->update($request->validated());
        
        if (session('discountTypes_url')) {
            return redirect(session('discountTypes_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.discount-types.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyDiscountTypeRequest $request, DiscountType $discountType): RedirectResponse
    {
        
        $discountType->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyDiscountTypeRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    DiscountType::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                DiscountType::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
