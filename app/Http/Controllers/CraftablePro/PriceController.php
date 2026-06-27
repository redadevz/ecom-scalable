<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\Price\BulkDestroyPriceRequest;
use App\Http\Requests\CraftablePro\Price\CreatePriceRequest;
use App\Http\Requests\CraftablePro\Price\DestroyPriceRequest;
use App\Http\Requests\CraftablePro\Price\EditPriceRequest;
use App\Http\Requests\CraftablePro\Price\IndexPriceRequest;
use App\Http\Requests\CraftablePro\Price\StorePriceRequest;
use App\Http\Requests\CraftablePro\Price\UpdatePriceRequest;
use App\Models\Price;
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

class PriceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexPriceRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $pricesQuery = QueryBuilder::for(Price::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'item_id', 'store_id', 'created_by', 'description', 'current_item_cost', 'markup_percentage', 'price_before_tax', 'tax_value', 'price_after_tax', 'sale_price', 'price_change_allowed', 'is_active', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'item_id', 'store_id', 'created_by', 'description', 'current_item_cost', 'markup_percentage', 'price_before_tax', 'tax_value', 'price_after_tax', 'sale_price', 'price_change_allowed', 'start_time', 'end_time', 'is_active', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($pricesQuery->select(['id'])->pluck('id'));
        }

        $prices = $pricesQuery
            ->with([])
            ->select('id', 'item_id', 'store_id', 'created_by', 'description', 'current_item_cost', 'markup_percentage', 'price_before_tax', 'tax_value', 'price_after_tax', 'sale_price', 'price_change_allowed', 'start_time', 'end_time', 'is_active', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('prices_url', $request->fullUrl());

        return Inertia::render('Price/Index', [
            'prices' => $prices,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreatePriceRequest $request): Response
    {
        return Inertia::render('Price/Create', [
            'craftable_pro_users' => \Brackets\CraftablePro\Models\CraftableProUser::orderBy('email')->get(['id', 'email']),
            'items' => \App\Models\Item::orderBy('name')->get(['id', 'name']),
            'stores' => \App\Models\Store::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StorePriceRequest $request): RedirectResponse
    {
        $price = Price::create($request->validated());
        
        return redirect()->route('craftable-pro.prices.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditPriceRequest $request, Price $price): Response
    {
        
        return Inertia::render('Price/Edit', [
            'price' => $price,
            'craftable_pro_users' => \Brackets\CraftablePro\Models\CraftableProUser::orderBy('email')->get(['id', 'email']),
            'items' => \App\Models\Item::orderBy('name')->get(['id', 'name']),
            'stores' => \App\Models\Store::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePriceRequest $request, Price $price): RedirectResponse
    {
        $price->update($request->validated());
        
        if (session('prices_url')) {
            return redirect(session('prices_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.prices.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyPriceRequest $request, Price $price): RedirectResponse
    {
        
        $price->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyPriceRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    Price::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                Price::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
