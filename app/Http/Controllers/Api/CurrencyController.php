<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Currency\DestroyCurrencyRequest;
use App\Http\Requests\Api\Currency\IndexCurrencyRequest;
use App\Http\Requests\Api\Currency\ShowCurrencyRequest;
use App\Http\Requests\Api\Currency\StoreCurrencyRequest;
use App\Http\Requests\Api\Currency\UpdateCurrencyRequest;
use App\Http\Resources\Api\CurrencyCollection;
use App\Http\Resources\Api\CurrencyResource;
use App\Models\Currency;
use Illuminate\Http\JsonResponse;

class CurrencyController extends Controller
{
    public function index(IndexCurrencyRequest $request): JsonResponse
    {
        $paginator = Currency::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new CurrencyCollection($paginator));
    }

    public function store(StoreCurrencyRequest $request): JsonResponse
    {
        $currency = Currency::query()->create($request->validated());
        
        return response()->json(new CurrencyResource($currency), 201);
    }

    public function update(UpdateCurrencyRequest $request, Currency $currency): JsonResponse
    {
        $currency->update($request->validated());
        
        return response()->json(new CurrencyResource($currency));
    }

    public function show(ShowCurrencyRequest $request, Currency $currency): JsonResponse
    {
        return response()->json(new CurrencyResource($currency));
    }

    public function destroy(DestroyCurrencyRequest $request, Currency $currency): JsonResponse
    {
        $currency->delete();

        return response()->json(null, 204);
    }
}
