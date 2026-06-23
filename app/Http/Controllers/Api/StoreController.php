<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Store\DestroyStoreRequest;
use App\Http\Requests\Api\Store\IndexStoreRequest;
use App\Http\Requests\Api\Store\ShowStoreRequest;
use App\Http\Requests\Api\Store\StoreStoreRequest;
use App\Http\Requests\Api\Store\UpdateStoreRequest;
use App\Http\Resources\Api\StoreCollection;
use App\Http\Resources\Api\StoreResource;
use App\Models\Store;
use Illuminate\Http\JsonResponse;

class StoreController extends Controller
{
    public function index(IndexStoreRequest $request): JsonResponse
    {
        $paginator = Store::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new StoreCollection($paginator));
    }

    public function store(StoreStoreRequest $request): JsonResponse
    {
        $store = Store::query()->create($request->validated());
        
        return response()->json(new StoreResource($store), 201);
    }

    public function update(UpdateStoreRequest $request, Store $store): JsonResponse
    {
        $store->update($request->validated());
        
        return response()->json(new StoreResource($store));
    }

    public function show(ShowStoreRequest $request, Store $store): JsonResponse
    {
        return response()->json(new StoreResource($store));
    }

    public function destroy(DestroyStoreRequest $request, Store $store): JsonResponse
    {
        $store->delete();

        return response()->json(null, 204);
    }
}
