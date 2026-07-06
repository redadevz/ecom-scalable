<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\LoyaltyCard\BulkDestroyLoyaltyCardRequest;
use App\Http\Requests\CraftablePro\LoyaltyCard\CreateLoyaltyCardRequest;
use App\Http\Requests\CraftablePro\LoyaltyCard\DestroyLoyaltyCardRequest;
use App\Http\Requests\CraftablePro\LoyaltyCard\EditLoyaltyCardRequest;
use App\Http\Requests\CraftablePro\LoyaltyCard\IndexLoyaltyCardRequest;
use App\Http\Requests\CraftablePro\LoyaltyCard\StoreLoyaltyCardRequest;
use App\Http\Requests\CraftablePro\LoyaltyCard\UpdateLoyaltyCardRequest;
use App\Models\LoyaltyCard;
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

class LoyaltyCardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexLoyaltyCardRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $loyaltyCardsQuery = QueryBuilder::for(LoyaltyCard::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'loyalty_card_type_id', 'customer_id', 'code', 'is_active'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'loyalty_card_type_id', 'customer_id', 'code', 'is_active', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($loyaltyCardsQuery->select(['id'])->pluck('id'));
        }

        $loyaltyCards = $loyaltyCardsQuery
            ->with([
                'customer:id,first_name,last_name,company_name,is_company',
                'loyaltyCardType:id,name',
            ])
            ->select('id', 'loyalty_card_type_id', 'customer_id', 'code', 'is_active', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('loyaltyCards_url', $request->fullUrl());

        return Inertia::render('LoyaltyCard/Index', [
            'loyaltyCards' => $loyaltyCards,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateLoyaltyCardRequest $request): Response
    {
        return Inertia::render('LoyaltyCard/Create', [
            'customers' => \App\Models\Customer::orderBy('code')->get(['id', 'code']),
            'loyalty_card_types' => \App\Models\LoyaltyCardType::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreLoyaltyCardRequest $request): RedirectResponse
    {
        $loyaltyCard = LoyaltyCard::create($request->validated());
        
        return redirect()->route('craftable-pro.loyalty-cards.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditLoyaltyCardRequest $request, LoyaltyCard $loyaltyCard): Response
    {
        
        return Inertia::render('LoyaltyCard/Edit', [
            'loyaltyCard' => $loyaltyCard,
            'customers' => \App\Models\Customer::orderBy('code')->get(['id', 'code']),
            'loyalty_card_types' => \App\Models\LoyaltyCardType::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLoyaltyCardRequest $request, LoyaltyCard $loyaltyCard): RedirectResponse
    {
        $loyaltyCard->update($request->validated());
        
        if (session('loyaltyCards_url')) {
            return redirect(session('loyaltyCards_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.loyalty-cards.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyLoyaltyCardRequest $request, LoyaltyCard $loyaltyCard): RedirectResponse
    {
        
        $loyaltyCard->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyLoyaltyCardRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    LoyaltyCard::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                LoyaltyCard::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
