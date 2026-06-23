<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\LoyaltyCardType\BulkDestroyLoyaltyCardTypeRequest;
use App\Http\Requests\CraftablePro\LoyaltyCardType\CreateLoyaltyCardTypeRequest;
use App\Http\Requests\CraftablePro\LoyaltyCardType\DestroyLoyaltyCardTypeRequest;
use App\Http\Requests\CraftablePro\LoyaltyCardType\EditLoyaltyCardTypeRequest;
use App\Http\Requests\CraftablePro\LoyaltyCardType\IndexLoyaltyCardTypeRequest;
use App\Http\Requests\CraftablePro\LoyaltyCardType\StoreLoyaltyCardTypeRequest;
use App\Http\Requests\CraftablePro\LoyaltyCardType\UpdateLoyaltyCardTypeRequest;
use App\Models\LoyaltyCardType;
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

class LoyaltyCardTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexLoyaltyCardTypeRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $loyaltyCardTypesQuery = QueryBuilder::for(LoyaltyCardType::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'name', 'description', 'is_active'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'name', 'description', 'is_active', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($loyaltyCardTypesQuery->select(['id'])->pluck('id'));
        }

        $loyaltyCardTypes = $loyaltyCardTypesQuery
            ->with([])
            ->select('id', 'name', 'description', 'is_active', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('loyaltyCardTypes_url', $request->fullUrl());

        return Inertia::render('LoyaltyCardType/Index', [
            'loyaltyCardTypes' => $loyaltyCardTypes,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateLoyaltyCardTypeRequest $request): Response
    {
        return Inertia::render('LoyaltyCardType/Create', [
            
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreLoyaltyCardTypeRequest $request): RedirectResponse
    {
        $loyaltyCardType = LoyaltyCardType::create($request->validated());
        
        return redirect()->route('craftable-pro.loyalty-card-types.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditLoyaltyCardTypeRequest $request, LoyaltyCardType $loyaltyCardType): Response
    {
        
        return Inertia::render('LoyaltyCardType/Edit', [
            'loyaltyCardType' => $loyaltyCardType,
            
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLoyaltyCardTypeRequest $request, LoyaltyCardType $loyaltyCardType): RedirectResponse
    {
        $loyaltyCardType->update($request->validated());
        
        if (session('loyaltyCardTypes_url')) {
            return redirect(session('loyaltyCardTypes_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.loyalty-card-types.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyLoyaltyCardTypeRequest $request, LoyaltyCardType $loyaltyCardType): RedirectResponse
    {
        
        $loyaltyCardType->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyLoyaltyCardTypeRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    LoyaltyCardType::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                LoyaltyCardType::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
