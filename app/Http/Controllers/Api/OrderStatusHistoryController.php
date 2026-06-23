<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OrderStatusHistory\DestroyOrderStatusHistoryRequest;
use App\Http\Requests\Api\OrderStatusHistory\IndexOrderStatusHistoryRequest;
use App\Http\Requests\Api\OrderStatusHistory\ShowOrderStatusHistoryRequest;
use App\Http\Requests\Api\OrderStatusHistory\StoreOrderStatusHistoryRequest;
use App\Http\Requests\Api\OrderStatusHistory\UpdateOrderStatusHistoryRequest;
use App\Http\Resources\Api\OrderStatusHistoryCollection;
use App\Http\Resources\Api\OrderStatusHistoryResource;
use App\Models\OrderStatusHistory;
use Illuminate\Http\JsonResponse;

class OrderStatusHistoryController extends Controller
{
    public function index(IndexOrderStatusHistoryRequest $request): JsonResponse
    {
        $paginator = OrderStatusHistory::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new OrderStatusHistoryCollection($paginator));
    }

    public function store(StoreOrderStatusHistoryRequest $request): JsonResponse
    {
        $orderStatusHistory = OrderStatusHistory::query()->create($request->validated());
        
        return response()->json(new OrderStatusHistoryResource($orderStatusHistory), 201);
    }

    public function update(UpdateOrderStatusHistoryRequest $request, OrderStatusHistory $orderStatusHistory): JsonResponse
    {
        $orderStatusHistory->update($request->validated());
        
        return response()->json(new OrderStatusHistoryResource($orderStatusHistory));
    }

    public function show(ShowOrderStatusHistoryRequest $request, OrderStatusHistory $orderStatusHistory): JsonResponse
    {
        return response()->json(new OrderStatusHistoryResource($orderStatusHistory));
    }

    public function destroy(DestroyOrderStatusHistoryRequest $request, OrderStatusHistory $orderStatusHistory): JsonResponse
    {
        $orderStatusHistory->delete();

        return response()->json(null, 204);
    }
}
