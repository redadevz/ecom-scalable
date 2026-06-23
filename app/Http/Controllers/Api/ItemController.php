<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Item\DestroyItemRequest;
use App\Http\Requests\Api\Item\IndexItemRequest;
use App\Http\Requests\Api\Item\ShowItemRequest;
use App\Http\Requests\Api\Item\StoreItemRequest;
use App\Http\Requests\Api\Item\UpdateItemRequest;
use App\Http\Resources\Api\ItemCollection;
use App\Http\Resources\Api\ItemResource;
use App\Models\Item;
use Illuminate\Http\JsonResponse;

class ItemController extends Controller
{
    public function index(IndexItemRequest $request): JsonResponse
    {
        $paginator = Item::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new ItemCollection($paginator));
    }

    public function store(StoreItemRequest $request): JsonResponse
    {
        $item = Item::query()->create($request->validated());
        
        return response()->json(new ItemResource($item), 201);
    }

    public function update(UpdateItemRequest $request, Item $item): JsonResponse
    {
        $item->update($request->validated());
        
        return response()->json(new ItemResource($item));
    }

    public function show(ShowItemRequest $request, Item $item): JsonResponse
    {
        return response()->json(new ItemResource($item));
    }

    public function destroy(DestroyItemRequest $request, Item $item): JsonResponse
    {
        $item->delete();

        return response()->json(null, 204);
    }
}
