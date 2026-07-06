<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\Customer\BulkDestroyCustomerRequest;
use App\Http\Requests\CraftablePro\Customer\CreateCustomerRequest;
use App\Http\Requests\CraftablePro\Customer\DestroyCustomerRequest;
use App\Http\Requests\CraftablePro\Customer\EditCustomerRequest;
use App\Http\Requests\CraftablePro\Customer\IndexCustomerRequest;
use App\Http\Requests\CraftablePro\Customer\StoreCustomerRequest;
use App\Http\Requests\CraftablePro\Customer\UpdateCustomerRequest;
use App\Models\Customer;
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

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexCustomerRequest $request): Response | JsonResponse | RedirectResponse
    {
        $defaultSort = "id";

        if (!$request->has('sort')) {
            return redirect()->route($request->route()->getName(), ['sort' => $defaultSort]);
        }

        $customersQuery = QueryBuilder::for(Customer::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'city_id', 'created_at_store_id', 'created_by', 'code', 'phone', 'first_name', 'last_name', 'is_company', 'company_name', 'tax_number', 'is_tax_exempted', 'billing_address', 'postal_code', 'is_registered_online', 'email', 'username', 'credit', 'is_active', 'comments'
                )),
            ])
            ->defaultSort($defaultSort)
            ->allowedSorts(['id', 'city_id', 'created_at_store_id', 'created_by', 'code', 'phone', 'first_name', 'last_name', 'is_company', 'company_name', 'tax_number', 'is_tax_exempted', 'billing_address', 'postal_code', 'is_registered_online', 'email', 'username', 'credit', 'last_login_time', 'is_active', 'comments', 'created_at']);

        if ($request->wantsJson() && $request->get('bulk_select_all')) {
            return response()->json($customersQuery->select(['id'])->pluck('id'));
        }

        $customers = $customersQuery
            ->with(['city:id,name'])
            ->select('id', 'city_id', 'created_at_store_id', 'created_by', 'code', 'phone', 'first_name', 'last_name', 'is_company', 'company_name', 'tax_number', 'is_tax_exempted', 'billing_address', 'postal_code', 'is_registered_online', 'email', 'username', 'credit', 'last_login_time', 'is_active', 'comments', 'created_at')
            ->paginate($request->get('per_page'))->withQueryString();

        Session::put('customers_url', $request->fullUrl());

        return Inertia::render('Customer/Index', [
            'customers' => $customers,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(CreateCustomerRequest $request): Response
    {
        return Inertia::render('Customer/Create', [
            'cities' => \App\Models\City::orderBy('name')->get(['id', 'name']),
            'stores' => \App\Models\Store::orderBy('name')->get(['id', 'name']),
            'craftable_pro_users' => \Brackets\CraftablePro\Models\CraftableProUser::orderBy('email')->get(['id', 'email']),
        ]);
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        $customer = Customer::create($request->validated());
        
        return redirect()->route('craftable-pro.customers.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditCustomerRequest $request, Customer $customer): Response
    {
        
        return Inertia::render('Customer/Edit', [
            'customer' => $customer,
            'cities' => \App\Models\City::orderBy('name')->get(['id', 'name']),
            'stores' => \App\Models\Store::orderBy('name')->get(['id', 'name']),
            'craftable_pro_users' => \Brackets\CraftablePro\Models\CraftableProUser::orderBy('email')->get(['id', 'email']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer): RedirectResponse
    {
        $customer->update($request->validated());
        
        if (session('customers_url')) {
            return redirect(session('customers_url'))->with(['message' => ___('craftable-pro', 'Operation successful')]);
        }

        return redirect()->route('craftable-pro.customers.index')->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyCustomerRequest $request, Customer $customer): RedirectResponse
    {
        
        $customer->delete();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }

    /**
     * Bulk destroy resource.
     */
    public function bulkDestroy(BulkDestroyCustomerRequest $request): RedirectResponse
    {
        // Mass delete of resource
        DB::transaction(function () use ($request) {
            collect($request->validated()['ids'])
                ->chunk(1000)
                ->each(function ($bulkChunk) {
                    Customer::whereIn('id', $bulkChunk)->delete();
                });
        });

        // Individual delete of resource items
        //        DB::transaction(function () use ($request) {
        //            collect($request->validated()['ids'])->each(function ($id) {
        //                Customer::find($id)->delete();
        //            });
        //        });

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
