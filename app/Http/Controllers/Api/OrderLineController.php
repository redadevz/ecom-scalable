<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OrderLine\DestroyOrderLineRequest;
use App\Http\Requests\Api\OrderLine\IndexOrderLineRequest;
use App\Http\Requests\Api\OrderLine\ShowOrderLineRequest;
use App\Http\Requests\Api\OrderLine\StoreOrderLineRequest;
use App\Http\Requests\Api\OrderLine\UpdateOrderLineRequest;
use App\Http\Resources\Api\OrderLineCollection;
use App\Http\Resources\Api\OrderLineResource;
use App\Models\OrderLine;
use Illuminate\Http\JsonResponse;

class OrderLineController extends Controller
{
    public function index(IndexOrderLineRequest $request): JsonResponse
    {
        $paginator = OrderLine::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new OrderLineCollection($paginator));
    }

    public function store(StoreOrderLineRequest $request): JsonResponse
    {
        $orderLine = OrderLine::query()->create($request->validated());
        
        return response()->json(new OrderLineResource($orderLine), 201);
    }

    public function update(UpdateOrderLineRequest $request, OrderLine $orderLine): JsonResponse
    {
        $orderLine->update($request->validated());
        
        return response()->json(new OrderLineResource($orderLine));
    }

    public function show(ShowOrderLineRequest $request, OrderLine $orderLine): JsonResponse
    {
        return response()->json(new OrderLineResource($orderLine));
    }

    public function destroy(DestroyOrderLineRequest $request, OrderLine $orderLine): JsonResponse
    {
        $orderLine->delete();

        return response()->json(null, 204);
    }
}
