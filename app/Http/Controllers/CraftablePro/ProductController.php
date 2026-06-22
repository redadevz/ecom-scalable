<?php

namespace App\Http\Controllers\CraftablePro;

use App\Exports\CraftablePro\ProductExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\Product\BulkDestroyProductRequest;
use App\Http\Requests\CraftablePro\Product\CreateProductRequest;
use App\Http\Requests\CraftablePro\Product\DestroyProductRequest;
use App\Http\Requests\CraftablePro\Product\EditProductRequest;
use App\Http\Requests\CraftablePro\Product\ExportProductRequest;
use App\Http\Requests\CraftablePro\Product\IndexProductRequest;
use App\Http\Requests\CraftablePro\Product\StoreProductRequest;
use App\Http\Requests\CraftablePro\Product\UpdateProductRequest;
use App\Models\Product;
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

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexProductRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $productsQuery = QueryBuilder::for(Product::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'name', 'slug', 'description', 'price', 'stock', 'category', 'image_url'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'name', 'slug', 'description', 'price', 'stock', 'category', 'image_url', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($productsQuery->select(['id'])->pluck('id'));
        }

        $products = $productsQuery
            ->with(['craftableProUser'])
            ->select('id', 'name', 'slug', 'description', 'price', 'stock', 'category', 'image_url', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('products_url', $request->fullUrl());

        return Inertia::render('Product/Index', [
            'products' => $products,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateProductRequest $request): Response
    {
        return Inertia::render('Product/Create', [
            'craftableProUserOptions' => \Brackets\CraftablePro\Models\CraftableProUser::all()->map(fn ($model) => ['value' => $model->id, 'label' => $model->email]),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $product = Product::create($request->validated());
        
        return redirect()->route('craftable-pro.products.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditProductRequest $request, Product $product): Response
    {
        
        return Inertia::render('Product/Edit', [
            'product' => $product,
            'craftableProUserOptions' => \Brackets\CraftablePro\Models\CraftableProUser::all()->map(fn ($model) => ['value' => $model->id, 'label' => $model->email]),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());
        
        if (session('products_url')) {
            return redirect(session('products_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.products.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyProductRequest $request, Product $product): RedirectResponse
    {
        
        $product->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyProductRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    Product::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                Product::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Export
     */
    public function export(ExportProductRequest $request): BinaryFileResponse
    {
        return Excel::download(new ProductExport($request->all()), 'products-'.now()->format("dmYHi").'.xlsx');
    }
}
