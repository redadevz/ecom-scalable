<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Country\DestroyCountryRequest;
use App\Http\Requests\Api\Country\IndexCountryRequest;
use App\Http\Requests\Api\Country\ShowCountryRequest;
use App\Http\Requests\Api\Country\StoreCountryRequest;
use App\Http\Requests\Api\Country\UpdateCountryRequest;
use App\Http\Resources\Api\CountryCollection;
use App\Http\Resources\Api\CountryResource;
use App\Models\Country;
use Illuminate\Http\JsonResponse;

class CountryController extends Controller
{
    public function index(IndexCountryRequest $request): JsonResponse
    {
        $paginator = Country::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new CountryCollection($paginator));
    }

    public function store(StoreCountryRequest $request): JsonResponse
    {
        $country = Country::query()->create($request->validated());
        
        return response()->json(new CountryResource($country), 201);
    }

    public function update(UpdateCountryRequest $request, Country $country): JsonResponse
    {
        $country->update($request->validated());
        
        return response()->json(new CountryResource($country));
    }

    public function show(ShowCountryRequest $request, Country $country): JsonResponse
    {
        return response()->json(new CountryResource($country));
    }

    public function destroy(DestroyCountryRequest $request, Country $country): JsonResponse
    {
        $country->delete();

        return response()->json(null, 204);
    }
}
