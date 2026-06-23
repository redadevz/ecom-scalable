<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OrderHeader\DestroyOrderHeaderRequest;
use App\Http\Requests\Api\OrderHeader\IndexOrderHeaderRequest;
use App\Http\Requests\Api\OrderHeader\ShowOrderHeaderRequest;
use App\Http\Requests\Api\OrderHeader\StoreOrderHeaderRequest;
use App\Http\Requests\Api\OrderHeader\UpdateOrderHeaderRequest;
use App\Http\Resources\Api\OrderHeaderCollection;
use App\Http\Resources\Api\OrderHeaderResource;
use App\Models\OrderHeader;
use Illuminate\Http\JsonResponse;

class OrderHeaderController extends Controller
{
    public function index(IndexOrderHeaderRequest $request): JsonResponse
    {
        $paginator = OrderHeader::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new OrderHeaderCollection($paginator));
    }

    public function store(StoreOrderHeaderRequest $request): JsonResponse
    {
        $orderHeader = OrderHeader::query()->create($request->validated());
        
        return response()->json(new OrderHeaderResource($orderHeader), 201);
    }

    public function update(UpdateOrderHeaderRequest $request, OrderHeader $orderHeader): JsonResponse
    {
        $orderHeader->update($request->validated());
        
        return response()->json(new OrderHeaderResource($orderHeader));
    }

    public function show(ShowOrderHeaderRequest $request, OrderHeader $orderHeader): JsonResponse
    {
        return response()->json(new OrderHeaderResource($orderHeader));
    }

    public function destroy(DestroyOrderHeaderRequest $request, OrderHeader $orderHeader): JsonResponse
    {
        $orderHeader->delete();

        return response()->json(null, 204);
    }
}
