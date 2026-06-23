<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MeasureUnit\DestroyMeasureUnitRequest;
use App\Http\Requests\Api\MeasureUnit\IndexMeasureUnitRequest;
use App\Http\Requests\Api\MeasureUnit\ShowMeasureUnitRequest;
use App\Http\Requests\Api\MeasureUnit\StoreMeasureUnitRequest;
use App\Http\Requests\Api\MeasureUnit\UpdateMeasureUnitRequest;
use App\Http\Resources\Api\MeasureUnitCollection;
use App\Http\Resources\Api\MeasureUnitResource;
use App\Models\MeasureUnit;
use Illuminate\Http\JsonResponse;

class MeasureUnitController extends Controller
{
    public function index(IndexMeasureUnitRequest $request): JsonResponse
    {
        $paginator = MeasureUnit::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new MeasureUnitCollection($paginator));
    }

    public function store(StoreMeasureUnitRequest $request): JsonResponse
    {
        $measureUnit = MeasureUnit::query()->create($request->validated());
        
        return response()->json(new MeasureUnitResource($measureUnit), 201);
    }

    public function update(UpdateMeasureUnitRequest $request, MeasureUnit $measureUnit): JsonResponse
    {
        $measureUnit->update($request->validated());
        
        return response()->json(new MeasureUnitResource($measureUnit));
    }

    public function show(ShowMeasureUnitRequest $request, MeasureUnit $measureUnit): JsonResponse
    {
        return response()->json(new MeasureUnitResource($measureUnit));
    }

    public function destroy(DestroyMeasureUnitRequest $request, MeasureUnit $measureUnit): JsonResponse
    {
        $measureUnit->delete();

        return response()->json(null, 204);
    }
}
