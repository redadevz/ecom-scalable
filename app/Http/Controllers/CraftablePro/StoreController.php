<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\Store\BulkDestroyStoreRequest;
use App\Http\Requests\CraftablePro\Store\CreateStoreRequest;
use App\Http\Requests\CraftablePro\Store\DestroyStoreRequest;
use App\Http\Requests\CraftablePro\Store\EditStoreRequest;
use App\Http\Requests\CraftablePro\Store\IndexStoreRequest;
use App\Http\Requests\CraftablePro\Store\StoreStoreRequest;
use App\Http\Requests\CraftablePro\Store\UpdateStoreRequest;
use App\Models\Store;
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

class StoreController extends Controller
{
    /**
     * Upload a store logo; returns its public URL.
     */
    public function uploadLogo(Request $request): JsonResponse
    {
        $request->validate([
            'logo' => ['required', 'image', 'max:5120'], // 5 MB
        ]);

        $path = $request->file('logo')->store('store-logos', 'public');

        return response()->json(['url' => \Illuminate\Support\Facades\Storage::url($path)]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexStoreRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $storesQuery = QueryBuilder::for(Store::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'city_id', 'language_id', 'currency_id', 'code', 'name', 'is_active', 'legal_entity_name', 'tax_code', 'address', 'registration_number', 'phone', 'fax', 'email', 'logo', 'bank_branch', 'bank_code', 'bank_account'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'city_id', 'language_id', 'currency_id', 'code', 'name', 'is_active', 'legal_entity_name', 'tax_code', 'address', 'registration_number', 'phone', 'fax', 'email', 'logo', 'bank_branch', 'bank_code', 'bank_account', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($storesQuery->select(['id'])->pluck('id'));
        }

        $stores = $storesQuery
            ->with([])
            ->select('id', 'city_id', 'language_id', 'currency_id', 'code', 'name', 'is_active', 'legal_entity_name', 'tax_code', 'address', 'registration_number', 'phone', 'fax', 'email', 'logo', 'bank_branch', 'bank_code', 'bank_account', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('stores_url', $request->fullUrl());

        return Inertia::render('Store/Index', [
            'stores' => $stores,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateStoreRequest $request): Response
    {
        return Inertia::render('Store/Create', [
            'cities' => \App\Models\City::orderBy('name')->get(['id', 'name']),
            'currencies' => \App\Models\Currency::orderBy('name')->get(['id', 'name']),
            'languages' => \App\Models\Language::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreStoreRequest $request): RedirectResponse
    {
        $store = Store::create($request->validated());
        
        return redirect()->route('craftable-pro.stores.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditStoreRequest $request, Store $store): Response
    {
        
        return Inertia::render('Store/Edit', [
            'store' => $store,
            'cities' => \App\Models\City::orderBy('name')->get(['id', 'name']),
            'currencies' => \App\Models\Currency::orderBy('name')->get(['id', 'name']),
            'languages' => \App\Models\Language::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStoreRequest $request, Store $store): RedirectResponse
    {
        $store->update($request->validated());
        
        if (session('stores_url')) {
            return redirect(session('stores_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.stores.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyStoreRequest $request, Store $store): RedirectResponse
    {
        
        $store->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyStoreRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    Store::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                Store::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
