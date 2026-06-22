<?php

namespace App\Http\Controllers\CraftablePro;

use App\Exports\CraftablePro\CartExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\Cart\BulkDestroyCartRequest;
use App\Http\Requests\CraftablePro\Cart\CreateCartRequest;
use App\Http\Requests\CraftablePro\Cart\DestroyCartRequest;
use App\Http\Requests\CraftablePro\Cart\EditCartRequest;
use App\Http\Requests\CraftablePro\Cart\ExportCartRequest;
use App\Http\Requests\CraftablePro\Cart\IndexCartRequest;
use App\Http\Requests\CraftablePro\Cart\StoreCartRequest;
use App\Http\Requests\CraftablePro\Cart\UpdateCartRequest;
use App\Models\Cart;
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

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexCartRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $cartsQuery = QueryBuilder::for(Cart::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'user_id'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'user_id', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($cartsQuery->select(['id'])->pluck('id'));
        }

        $carts = $cartsQuery
            ->with([])
            ->select('id', 'user_id', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('carts_url', $request->fullUrl());

        return Inertia::render('Cart/Index', [
            'carts' => $carts,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateCartRequest $request): Response
    {
        return Inertia::render('Cart/Create', [
            
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreCartRequest $request): RedirectResponse
    {
        $cart = Cart::create($request->validated());
        
        return redirect()->route('craftable-pro.carts.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditCartRequest $request, Cart $cart): Response
    {
        
        return Inertia::render('Cart/Edit', [
            'cart' => $cart,
            
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartRequest $request, Cart $cart): RedirectResponse
    {
        $cart->update($request->validated());
        
        if (session('carts_url')) {
            return redirect(session('carts_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.carts.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyCartRequest $request, Cart $cart): RedirectResponse
    {
        
        $cart->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyCartRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    Cart::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                Cart::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Export
     */
    public function export(ExportCartRequest $request): BinaryFileResponse
    {
        return Excel::download(new CartExport($request->all()), 'carts-'.now()->format("dmYHi").'.xlsx');
    }
}
