<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\City\DestroyCityRequest;
use App\Http\Requests\Api\City\IndexCityRequest;
use App\Http\Requests\Api\City\ShowCityRequest;
use App\Http\Requests\Api\City\StoreCityRequest;
use App\Http\Requests\Api\City\UpdateCityRequest;
use App\Http\Resources\Api\CityCollection;
use App\Http\Resources\Api\CityResource;
use App\Models\City;
use Illuminate\Http\JsonResponse;

class CityController extends Controller
{
    public function index(IndexCityRequest $request): JsonResponse
    {
        $paginator = City::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new CityCollection($paginator));
    }

    public function store(StoreCityRequest $request): JsonResponse
    {
        $city = City::query()->create($request->validated());
        
        return response()->json(new CityResource($city), 201);
    }

    public function update(UpdateCityRequest $request, City $city): JsonResponse
    {
        $city->update($request->validated());
        
        return response()->json(new CityResource($city));
    }

    public function show(ShowCityRequest $request, City $city): JsonResponse
    {
        return response()->json(new CityResource($city));
    }

    public function destroy(DestroyCityRequest $request, City $city): JsonResponse
    {
        $city->delete();

        return response()->json(null, 204);
    }
}
