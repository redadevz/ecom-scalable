<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DeliveryType\DestroyDeliveryTypeRequest;
use App\Http\Requests\Api\DeliveryType\IndexDeliveryTypeRequest;
use App\Http\Requests\Api\DeliveryType\ShowDeliveryTypeRequest;
use App\Http\Requests\Api\DeliveryType\StoreDeliveryTypeRequest;
use App\Http\Requests\Api\DeliveryType\UpdateDeliveryTypeRequest;
use App\Http\Resources\Api\DeliveryTypeCollection;
use App\Http\Resources\Api\DeliveryTypeResource;
use App\Models\DeliveryType;
use Illuminate\Http\JsonResponse;

class DeliveryTypeController extends Controller
{
    public function index(IndexDeliveryTypeRequest $request): JsonResponse
    {
        $paginator = DeliveryType::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new DeliveryTypeCollection($paginator));
    }

    public function store(StoreDeliveryTypeRequest $request): JsonResponse
    {
        $deliveryType = DeliveryType::query()->create($request->validated());
        
        return response()->json(new DeliveryTypeResource($deliveryType), 201);
    }

    public function update(UpdateDeliveryTypeRequest $request, DeliveryType $deliveryType): JsonResponse
    {
        $deliveryType->update($request->validated());
        
        return response()->json(new DeliveryTypeResource($deliveryType));
    }

    public function show(ShowDeliveryTypeRequest $request, DeliveryType $deliveryType): JsonResponse
    {
        return response()->json(new DeliveryTypeResource($deliveryType));
    }

    public function destroy(DestroyDeliveryTypeRequest $request, DeliveryType $deliveryType): JsonResponse
    {
        $deliveryType->delete();

        return response()->json(null, 204);
    }
}
