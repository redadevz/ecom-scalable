<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Language\DestroyLanguageRequest;
use App\Http\Requests\Api\Language\IndexLanguageRequest;
use App\Http\Requests\Api\Language\ShowLanguageRequest;
use App\Http\Requests\Api\Language\StoreLanguageRequest;
use App\Http\Requests\Api\Language\UpdateLanguageRequest;
use App\Http\Resources\Api\LanguageCollection;
use App\Http\Resources\Api\LanguageResource;
use App\Models\Language;
use Illuminate\Http\JsonResponse;

class LanguageController extends Controller
{
    public function index(IndexLanguageRequest $request): JsonResponse
    {
        $paginator = Language::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new LanguageCollection($paginator));
    }

    public function store(StoreLanguageRequest $request): JsonResponse
    {
        $language = Language::query()->create($request->validated());
        
        return response()->json(new LanguageResource($language), 201);
    }

    public function update(UpdateLanguageRequest $request, Language $language): JsonResponse
    {
        $language->update($request->validated());
        
        return response()->json(new LanguageResource($language));
    }

    public function show(ShowLanguageRequest $request, Language $language): JsonResponse
    {
        return response()->json(new LanguageResource($language));
    }

    public function destroy(DestroyLanguageRequest $request, Language $language): JsonResponse
    {
        $language->delete();

        return response()->json(null, 204);
    }
}
