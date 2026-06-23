<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TimeZone\DestroyTimeZoneRequest;
use App\Http\Requests\Api\TimeZone\IndexTimeZoneRequest;
use App\Http\Requests\Api\TimeZone\ShowTimeZoneRequest;
use App\Http\Requests\Api\TimeZone\StoreTimeZoneRequest;
use App\Http\Requests\Api\TimeZone\UpdateTimeZoneRequest;
use App\Http\Resources\Api\TimeZoneCollection;
use App\Http\Resources\Api\TimeZoneResource;
use App\Models\TimeZone;
use Illuminate\Http\JsonResponse;

class TimeZoneController extends Controller
{
    public function index(IndexTimeZoneRequest $request): JsonResponse
    {
        $paginator = TimeZone::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new TimeZoneCollection($paginator));
    }

    public function store(StoreTimeZoneRequest $request): JsonResponse
    {
        $timeZone = TimeZone::query()->create($request->validated());
        
        return response()->json(new TimeZoneResource($timeZone), 201);
    }

    public function update(UpdateTimeZoneRequest $request, TimeZone $timeZone): JsonResponse
    {
        $timeZone->update($request->validated());
        
        return response()->json(new TimeZoneResource($timeZone));
    }

    public function show(ShowTimeZoneRequest $request, TimeZone $timeZone): JsonResponse
    {
        return response()->json(new TimeZoneResource($timeZone));
    }

    public function destroy(DestroyTimeZoneRequest $request, TimeZone $timeZone): JsonResponse
    {
        $timeZone->delete();

        return response()->json(null, 204);
    }
}
