<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\SaleReturnItem\BulkDestroySaleReturnItemRequest;
use App\Http\Requests\CraftablePro\SaleReturnItem\CreateSaleReturnItemRequest;
use App\Http\Requests\CraftablePro\SaleReturnItem\DestroySaleReturnItemRequest;
use App\Http\Requests\CraftablePro\SaleReturnItem\EditSaleReturnItemRequest;
use App\Http\Requests\CraftablePro\SaleReturnItem\IndexSaleReturnItemRequest;
use App\Http\Requests\CraftablePro\SaleReturnItem\StoreSaleReturnItemRequest;
use App\Http\Requests\CraftablePro\SaleReturnItem\UpdateSaleReturnItemRequest;
use App\Models\SaleReturnItem;
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

class SaleReturnItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexSaleReturnItemRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $saleReturnItemsQuery = QueryBuilder::for(SaleReturnItem::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'sale_return_id', 'order_line_id', 'item_id', 'line_no', 'quantity', 'return_quantity', 'description', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'sale_return_id', 'order_line_id', 'item_id', 'line_no', 'quantity', 'return_quantity', 'description', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($saleReturnItemsQuery->select(['id'])->pluck('id'));
        }

        $saleReturnItems = $saleReturnItemsQuery
            ->with(['item:id,name', 'saleReturn:id'])
            ->select('id', 'sale_return_id', 'order_line_id', 'item_id', 'line_no', 'quantity', 'return_quantity', 'description', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('saleReturnItems_url', $request->fullUrl());

        return Inertia::render('SaleReturnItem/Index', [
            'saleReturnItems' => $saleReturnItems,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateSaleReturnItemRequest $request): Response
    {
        return Inertia::render('SaleReturnItem/Create', [
            'items' => \App\Models\Item::orderBy('name')->get(['id', 'name']),
            'order_lines' => \App\Models\OrderLine::orderBy('line_no')->get(['id', 'line_no']),
            'sale_returns' => \App\Models\SaleReturn::orderBy('id')->get(['id', 'description'])->map(fn ($m) => ['id' => $m->id, 'description' => '#' . $m->id . ' ' . ($m->description ?? '')]),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreSaleReturnItemRequest $request): RedirectResponse
    {
        $saleReturnItem = SaleReturnItem::create($request->validated());
        
        return redirect()->route('craftable-pro.sale-return-items.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditSaleReturnItemRequest $request, SaleReturnItem $saleReturnItem): Response
    {
        
        return Inertia::render('SaleReturnItem/Edit', [
            'saleReturnItem' => $saleReturnItem,
            'items' => \App\Models\Item::orderBy('name')->get(['id', 'name']),
            'order_lines' => \App\Models\OrderLine::orderBy('line_no')->get(['id', 'line_no']),
            'sale_returns' => \App\Models\SaleReturn::orderBy('id')->get(['id', 'description'])->map(fn ($m) => ['id' => $m->id, 'description' => '#' . $m->id . ' ' . ($m->description ?? '')]),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSaleReturnItemRequest $request, SaleReturnItem $saleReturnItem): RedirectResponse
    {
        $saleReturnItem->update($request->validated());
        
        if (session('saleReturnItems_url')) {
            return redirect(session('saleReturnItems_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.sale-return-items.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroySaleReturnItemRequest $request, SaleReturnItem $saleReturnItem): RedirectResponse
    {
        
        $saleReturnItem->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroySaleReturnItemRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    SaleReturnItem::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                SaleReturnItem::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
