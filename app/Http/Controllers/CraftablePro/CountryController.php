<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\Country\BulkDestroyCountryRequest;
use App\Http\Requests\CraftablePro\Country\CreateCountryRequest;
use App\Http\Requests\CraftablePro\Country\DestroyCountryRequest;
use App\Http\Requests\CraftablePro\Country\EditCountryRequest;
use App\Http\Requests\CraftablePro\Country\IndexCountryRequest;
use App\Http\Requests\CraftablePro\Country\StoreCountryRequest;
use App\Http\Requests\CraftablePro\Country\UpdateCountryRequest;
use App\Models\Country;
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

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexCountryRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $countriesQuery = QueryBuilder::for(Country::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'name'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'name', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($countriesQuery->select(['id'])->pluck('id'));
        }

        $countries = $countriesQuery
            ->with([])
            ->select('id', 'name', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('countries_url', $request->fullUrl());

        return Inertia::render('Country/Index', [
            'countries' => $countries,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateCountryRequest $request): Response
    {
        return Inertia::render('Country/Create', [
            
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreCountryRequest $request): RedirectResponse
    {
        $country = Country::create($request->validated());
        
        return redirect()->route('craftable-pro.countries.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditCountryRequest $request, Country $country): Response
    {
        
        return Inertia::render('Country/Edit', [
            'country' => $country,
            
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCountryRequest $request, Country $country): RedirectResponse
    {
        $country->update($request->validated());
        
        if (session('countries_url')) {
            return redirect(session('countries_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.countries.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyCountryRequest $request, Country $country): RedirectResponse
    {
        
        $country->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyCountryRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    Country::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                Country::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
