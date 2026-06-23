<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Region\DestroyRegionRequest;
use App\Http\Requests\Api\Region\IndexRegionRequest;
use App\Http\Requests\Api\Region\ShowRegionRequest;
use App\Http\Requests\Api\Region\StoreRegionRequest;
use App\Http\Requests\Api\Region\UpdateRegionRequest;
use App\Http\Resources\Api\RegionCollection;
use App\Http\Resources\Api\RegionResource;
use App\Models\Region;
use Illuminate\Http\JsonResponse;

class RegionController extends Controller
{
    public function index(IndexRegionRequest $request): JsonResponse
    {
        $paginator = Region::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new RegionCollection($paginator));
    }

    public function store(StoreRegionRequest $request): JsonResponse
    {
        $region = Region::query()->create($request->validated());
        
        return response()->json(new RegionResource($region), 201);
    }

    public function update(UpdateRegionRequest $request, Region $region): JsonResponse
    {
        $region->update($request->validated());
        
        return response()->json(new RegionResource($region));
    }

    public function show(ShowRegionRequest $request, Region $region): JsonResponse
    {
        return response()->json(new RegionResource($region));
    }

    public function destroy(DestroyRegionRequest $request, Region $region): JsonResponse
    {
        $region->delete();

        return response()->json(null, 204);
    }
}
