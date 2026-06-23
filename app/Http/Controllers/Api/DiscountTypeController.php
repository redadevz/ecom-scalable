<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DiscountType\DestroyDiscountTypeRequest;
use App\Http\Requests\Api\DiscountType\IndexDiscountTypeRequest;
use App\Http\Requests\Api\DiscountType\ShowDiscountTypeRequest;
use App\Http\Requests\Api\DiscountType\StoreDiscountTypeRequest;
use App\Http\Requests\Api\DiscountType\UpdateDiscountTypeRequest;
use App\Http\Resources\Api\DiscountTypeCollection;
use App\Http\Resources\Api\DiscountTypeResource;
use App\Models\DiscountType;
use Illuminate\Http\JsonResponse;

class DiscountTypeController extends Controller
{
    public function index(IndexDiscountTypeRequest $request): JsonResponse
    {
        $paginator = DiscountType::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new DiscountTypeCollection($paginator));
    }

    public function store(StoreDiscountTypeRequest $request): JsonResponse
    {
        $discountType = DiscountType::query()->create($request->validated());
        
        return response()->json(new DiscountTypeResource($discountType), 201);
    }

    public function update(UpdateDiscountTypeRequest $request, DiscountType $discountType): JsonResponse
    {
        $discountType->update($request->validated());
        
        return response()->json(new DiscountTypeResource($discountType));
    }

    public function show(ShowDiscountTypeRequest $request, DiscountType $discountType): JsonResponse
    {
        return response()->json(new DiscountTypeResource($discountType));
    }

    public function destroy(DestroyDiscountTypeRequest $request, DiscountType $discountType): JsonResponse
    {
        $discountType->delete();

        return response()->json(null, 204);
    }
}
