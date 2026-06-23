<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoyaltyCard\DestroyLoyaltyCardRequest;
use App\Http\Requests\Api\LoyaltyCard\IndexLoyaltyCardRequest;
use App\Http\Requests\Api\LoyaltyCard\ShowLoyaltyCardRequest;
use App\Http\Requests\Api\LoyaltyCard\StoreLoyaltyCardRequest;
use App\Http\Requests\Api\LoyaltyCard\UpdateLoyaltyCardRequest;
use App\Http\Resources\Api\LoyaltyCardCollection;
use App\Http\Resources\Api\LoyaltyCardResource;
use App\Models\LoyaltyCard;
use Illuminate\Http\JsonResponse;

class LoyaltyCardController extends Controller
{
    public function index(IndexLoyaltyCardRequest $request): JsonResponse
    {
        $paginator = LoyaltyCard::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new LoyaltyCardCollection($paginator));
    }

    public function store(StoreLoyaltyCardRequest $request): JsonResponse
    {
        $loyaltyCard = LoyaltyCard::query()->create($request->validated());
        
        return response()->json(new LoyaltyCardResource($loyaltyCard), 201);
    }

    public function update(UpdateLoyaltyCardRequest $request, LoyaltyCard $loyaltyCard): JsonResponse
    {
        $loyaltyCard->update($request->validated());
        
        return response()->json(new LoyaltyCardResource($loyaltyCard));
    }

    public function show(ShowLoyaltyCardRequest $request, LoyaltyCard $loyaltyCard): JsonResponse
    {
        return response()->json(new LoyaltyCardResource($loyaltyCard));
    }

    public function destroy(DestroyLoyaltyCardRequest $request, LoyaltyCard $loyaltyCard): JsonResponse
    {
        $loyaltyCard->delete();

        return response()->json(null, 204);
    }
}
