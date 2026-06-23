<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Discount\DestroyDiscountRequest;
use App\Http\Requests\Api\Discount\IndexDiscountRequest;
use App\Http\Requests\Api\Discount\ShowDiscountRequest;
use App\Http\Requests\Api\Discount\StoreDiscountRequest;
use App\Http\Requests\Api\Discount\UpdateDiscountRequest;
use App\Http\Resources\Api\DiscountCollection;
use App\Http\Resources\Api\DiscountResource;
use App\Models\Discount;
use Illuminate\Http\JsonResponse;

class DiscountController extends Controller
{
    public function index(IndexDiscountRequest $request): JsonResponse
    {
        $paginator = Discount::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new DiscountCollection($paginator));
    }

    public function store(StoreDiscountRequest $request): JsonResponse
    {
        $discount = Discount::query()->create($request->validated());
        
        return response()->json(new DiscountResource($discount), 201);
    }

    public function update(UpdateDiscountRequest $request, Discount $discount): JsonResponse
    {
        $discount->update($request->validated());
        
        return response()->json(new DiscountResource($discount));
    }

    public function show(ShowDiscountRequest $request, Discount $discount): JsonResponse
    {
        return response()->json(new DiscountResource($discount));
    }

    public function destroy(DestroyDiscountRequest $request, Discount $discount): JsonResponse
    {
        $discount->delete();

        return response()->json(null, 204);
    }
}
