<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OrderStatus\DestroyOrderStatusRequest;
use App\Http\Requests\Api\OrderStatus\IndexOrderStatusRequest;
use App\Http\Requests\Api\OrderStatus\ShowOrderStatusRequest;
use App\Http\Requests\Api\OrderStatus\StoreOrderStatusRequest;
use App\Http\Requests\Api\OrderStatus\UpdateOrderStatusRequest;
use App\Http\Resources\Api\OrderStatusCollection;
use App\Http\Resources\Api\OrderStatusResource;
use App\Models\OrderStatus;
use Illuminate\Http\JsonResponse;

class OrderStatusController extends Controller
{
    public function index(IndexOrderStatusRequest $request): JsonResponse
    {
        $paginator = OrderStatus::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new OrderStatusCollection($paginator));
    }

    public function store(StoreOrderStatusRequest $request): JsonResponse
    {
        $orderStatus = OrderStatus::query()->create($request->validated());
        
        return response()->json(new OrderStatusResource($orderStatus), 201);
    }

    public function update(UpdateOrderStatusRequest $request, OrderStatus $orderStatus): JsonResponse
    {
        $orderStatus->update($request->validated());
        
        return response()->json(new OrderStatusResource($orderStatus));
    }

    public function show(ShowOrderStatusRequest $request, OrderStatus $orderStatus): JsonResponse
    {
        return response()->json(new OrderStatusResource($orderStatus));
    }

    public function destroy(DestroyOrderStatusRequest $request, OrderStatus $orderStatus): JsonResponse
    {
        $orderStatus->delete();

        return response()->json(null, 204);
    }
}
