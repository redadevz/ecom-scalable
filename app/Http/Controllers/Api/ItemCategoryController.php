<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ItemCategory\DestroyItemCategoryRequest;
use App\Http\Requests\Api\ItemCategory\IndexItemCategoryRequest;
use App\Http\Requests\Api\ItemCategory\ShowItemCategoryRequest;
use App\Http\Requests\Api\ItemCategory\StoreItemCategoryRequest;
use App\Http\Requests\Api\ItemCategory\UpdateItemCategoryRequest;
use App\Http\Resources\Api\ItemCategoryCollection;
use App\Http\Resources\Api\ItemCategoryResource;
use App\Models\ItemCategory;
use Illuminate\Http\JsonResponse;

class ItemCategoryController extends Controller
{
    public function index(IndexItemCategoryRequest $request): JsonResponse
    {
        $paginator = ItemCategory::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new ItemCategoryCollection($paginator));
    }

    public function store(StoreItemCategoryRequest $request): JsonResponse
    {
        $itemCategory = ItemCategory::query()->create($request->validated());
        
        return response()->json(new ItemCategoryResource($itemCategory), 201);
    }

    public function update(UpdateItemCategoryRequest $request, ItemCategory $itemCategory): JsonResponse
    {
        $itemCategory->update($request->validated());
        
        return response()->json(new ItemCategoryResource($itemCategory));
    }

    public function show(ShowItemCategoryRequest $request, ItemCategory $itemCategory): JsonResponse
    {
        return response()->json(new ItemCategoryResource($itemCategory));
    }

    public function destroy(DestroyItemCategoryRequest $request, ItemCategory $itemCategory): JsonResponse
    {
        $itemCategory->delete();

        return response()->json(null, 204);
    }
}
