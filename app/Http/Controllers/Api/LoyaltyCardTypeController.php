<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoyaltyCardType\DestroyLoyaltyCardTypeRequest;
use App\Http\Requests\Api\LoyaltyCardType\IndexLoyaltyCardTypeRequest;
use App\Http\Requests\Api\LoyaltyCardType\ShowLoyaltyCardTypeRequest;
use App\Http\Requests\Api\LoyaltyCardType\StoreLoyaltyCardTypeRequest;
use App\Http\Requests\Api\LoyaltyCardType\UpdateLoyaltyCardTypeRequest;
use App\Http\Resources\Api\LoyaltyCardTypeCollection;
use App\Http\Resources\Api\LoyaltyCardTypeResource;
use App\Models\LoyaltyCardType;
use Illuminate\Http\JsonResponse;

class LoyaltyCardTypeController extends Controller
{
    public function index(IndexLoyaltyCardTypeRequest $request): JsonResponse
    {
        $paginator = LoyaltyCardType::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new LoyaltyCardTypeCollection($paginator));
    }

    public function store(StoreLoyaltyCardTypeRequest $request): JsonResponse
    {
        $loyaltyCardType = LoyaltyCardType::query()->create($request->validated());
        
        return response()->json(new LoyaltyCardTypeResource($loyaltyCardType), 201);
    }

    public function update(UpdateLoyaltyCardTypeRequest $request, LoyaltyCardType $loyaltyCardType): JsonResponse
    {
        $loyaltyCardType->update($request->validated());
        
        return response()->json(new LoyaltyCardTypeResource($loyaltyCardType));
    }

    public function show(ShowLoyaltyCardTypeRequest $request, LoyaltyCardType $loyaltyCardType): JsonResponse
    {
        return response()->json(new LoyaltyCardTypeResource($loyaltyCardType));
    }

    public function destroy(DestroyLoyaltyCardTypeRequest $request, LoyaltyCardType $loyaltyCardType): JsonResponse
    {
        $loyaltyCardType->delete();

        return response()->json(null, 204);
    }
}
