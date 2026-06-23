<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\Currency\BulkDestroyCurrencyRequest;
use App\Http\Requests\CraftablePro\Currency\CreateCurrencyRequest;
use App\Http\Requests\CraftablePro\Currency\DestroyCurrencyRequest;
use App\Http\Requests\CraftablePro\Currency\EditCurrencyRequest;
use App\Http\Requests\CraftablePro\Currency\IndexCurrencyRequest;
use App\Http\Requests\CraftablePro\Currency\StoreCurrencyRequest;
use App\Http\Requests\CraftablePro\Currency\UpdateCurrencyRequest;
use App\Models\Currency;
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

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexCurrencyRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $currenciesQuery = QueryBuilder::for(Currency::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'name', 'short_name', 'symbol', 'description'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'name', 'short_name', 'symbol', 'description', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($currenciesQuery->select(['id'])->pluck('id'));
        }

        $currencies = $currenciesQuery
            ->with([])
            ->select('id', 'name', 'short_name', 'symbol', 'description', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('currencies_url', $request->fullUrl());

        return Inertia::render('Currency/Index', [
            'currencies' => $currencies,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateCurrencyRequest $request): Response
    {
        return Inertia::render('Currency/Create', [
            
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreCurrencyRequest $request): RedirectResponse
    {
        $currency = Currency::create($request->validated());
        
        return redirect()->route('craftable-pro.currencies.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditCurrencyRequest $request, Currency $currency): Response
    {
        
        return Inertia::render('Currency/Edit', [
            'currency' => $currency,
            
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCurrencyRequest $request, Currency $currency): RedirectResponse
    {
        $currency->update($request->validated());
        
        if (session('currencies_url')) {
            return redirect(session('currencies_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.currencies.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyCurrencyRequest $request, Currency $currency): RedirectResponse
    {
        
        $currency->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyCurrencyRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    Currency::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                Currency::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
