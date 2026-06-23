<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BarCode\DestroyBarCodeRequest;
use App\Http\Requests\Api\BarCode\IndexBarCodeRequest;
use App\Http\Requests\Api\BarCode\ShowBarCodeRequest;
use App\Http\Requests\Api\BarCode\StoreBarCodeRequest;
use App\Http\Requests\Api\BarCode\UpdateBarCodeRequest;
use App\Http\Resources\Api\BarCodeCollection;
use App\Http\Resources\Api\BarCodeResource;
use App\Models\BarCode;
use Illuminate\Http\JsonResponse;

class BarCodeController extends Controller
{
    public function index(IndexBarCodeRequest $request): JsonResponse
    {
        $paginator = BarCode::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new BarCodeCollection($paginator));
    }

    public function store(StoreBarCodeRequest $request): JsonResponse
    {
        $barCode = BarCode::query()->create($request->validated());
        
        return response()->json(new BarCodeResource($barCode), 201);
    }

    public function update(UpdateBarCodeRequest $request, BarCode $barCode): JsonResponse
    {
        $barCode->update($request->validated());
        
        return response()->json(new BarCodeResource($barCode));
    }

    public function show(ShowBarCodeRequest $request, BarCode $barCode): JsonResponse
    {
        return response()->json(new BarCodeResource($barCode));
    }

    public function destroy(DestroyBarCodeRequest $request, BarCode $barCode): JsonResponse
    {
        $barCode->delete();

        return response()->json(null, 204);
    }
}
