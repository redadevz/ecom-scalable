<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Customer\DestroyCustomerRequest;
use App\Http\Requests\Api\Customer\IndexCustomerRequest;
use App\Http\Requests\Api\Customer\ShowCustomerRequest;
use App\Http\Requests\Api\Customer\StoreCustomerRequest;
use App\Http\Requests\Api\Customer\UpdateCustomerRequest;
use App\Http\Resources\Api\CustomerCollection;
use App\Http\Resources\Api\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    public function index(IndexCustomerRequest $request): JsonResponse
    {
        $paginator = Customer::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new CustomerCollection($paginator));
    }

    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $customer = Customer::query()->create($request->validated());
        
        return response()->json(new CustomerResource($customer), 201);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): JsonResponse
    {
        $customer->update($request->validated());
        
        return response()->json(new CustomerResource($customer));
    }

    public function show(ShowCustomerRequest $request, Customer $customer): JsonResponse
    {
        return response()->json(new CustomerResource($customer));
    }

    public function destroy(DestroyCustomerRequest $request, Customer $customer): JsonResponse
    {
        $customer->delete();

        return response()->json(null, 204);
    }
}
