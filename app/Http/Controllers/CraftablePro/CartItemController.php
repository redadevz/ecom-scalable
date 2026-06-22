<?php

namespace App\Http\Controllers\CraftablePro;

use App\Exports\CraftablePro\CartItemExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\CartItem\BulkDestroyCartItemRequest;
use App\Http\Requests\CraftablePro\CartItem\CreateCartItemRequest;
use App\Http\Requests\CraftablePro\CartItem\DestroyCartItemRequest;
use App\Http\Requests\CraftablePro\CartItem\EditCartItemRequest;
use App\Http\Requests\CraftablePro\CartItem\ExportCartItemRequest;
use App\Http\Requests\CraftablePro\CartItem\IndexCartItemRequest;
use App\Http\Requests\CraftablePro\CartItem\StoreCartItemRequest;
use App\Http\Requests\CraftablePro\CartItem\UpdateCartItemRequest;
use App\Models\CartItem;
use Brackets\CraftablePro\Queries\Filters\FuzzyFilter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CartItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexCartItemRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $cartItemsQuery = QueryBuilder::for(CartItem::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'cart_id', 'product_id', 'quantity', 'unit_price'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'cart_id', 'product_id', 'quantity', 'unit_price', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($cartItemsQuery->select(['id'])->pluck('id'));
        }

        $cartItems = $cartItemsQuery
            ->with([])
            ->select('id', 'cart_id', 'product_id', 'quantity', 'unit_price', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('cartItems_url', $request->fullUrl());

        return Inertia::render('CartItem/Index', [
            'cartItems' => $cartItems,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateCartItemRequest $request): Response
    {
        return Inertia::render('CartItem/Create', [
            
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreCartItemRequest $request): RedirectResponse
    {
        $cartItem = CartItem::create($request->validated());
        
        return redirect()->route('craftable-pro.cart-items.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditCartItemRequest $request, CartItem $cartItem): Response
    {
        
        return Inertia::render('CartItem/Edit', [
            'cartItem' => $cartItem,
            
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartItemRequest $request, CartItem $cartItem): RedirectResponse
    {
        $cartItem->update($request->validated());
        
        if (session('cartItems_url')) {
            return redirect(session('cartItems_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.cart-items.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyCartItemRequest $request, CartItem $cartItem): RedirectResponse
    {
        
        $cartItem->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyCartItemRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    CartItem::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                CartItem::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Export
     */
    public function export(ExportCartItemRequest $request): BinaryFileResponse
    {
        return Excel::download(new CartItemExport($request->all()), 'cartitems-'.now()->format("dmYHi").'.xlsx');
    }
}
