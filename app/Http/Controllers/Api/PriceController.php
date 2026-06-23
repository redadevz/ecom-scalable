<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Price\DestroyPriceRequest;
use App\Http\Requests\Api\Price\IndexPriceRequest;
use App\Http\Requests\Api\Price\ShowPriceRequest;
use App\Http\Requests\Api\Price\StorePriceRequest;
use App\Http\Requests\Api\Price\UpdatePriceRequest;
use App\Http\Resources\Api\PriceCollection;
use App\Http\Resources\Api\PriceResource;
use App\Models\Price;
use Illuminate\Http\JsonResponse;

class PriceController extends Controller
{
    public function index(IndexPriceRequest $request): JsonResponse
    {
        $paginator = Price::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new PriceCollection($paginator));
    }

    public function store(StorePriceRequest $request): JsonResponse
    {
        $price = Price::query()->create($request->validated());
        
        return response()->json(new PriceResource($price), 201);
    }

    public function update(UpdatePriceRequest $request, Price $price): JsonResponse
    {
        $price->update($request->validated());
        
        return response()->json(new PriceResource($price));
    }

    public function show(ShowPriceRequest $request, Price $price): JsonResponse
    {
        return response()->json(new PriceResource($price));
    }

    public function destroy(DestroyPriceRequest $request, Price $price): JsonResponse
    {
        $price->delete();

        return response()->json(null, 204);
    }
}
