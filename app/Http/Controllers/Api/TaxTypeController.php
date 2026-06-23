<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TaxType\DestroyTaxTypeRequest;
use App\Http\Requests\Api\TaxType\IndexTaxTypeRequest;
use App\Http\Requests\Api\TaxType\ShowTaxTypeRequest;
use App\Http\Requests\Api\TaxType\StoreTaxTypeRequest;
use App\Http\Requests\Api\TaxType\UpdateTaxTypeRequest;
use App\Http\Resources\Api\TaxTypeCollection;
use App\Http\Resources\Api\TaxTypeResource;
use App\Models\TaxType;
use Illuminate\Http\JsonResponse;

class TaxTypeController extends Controller
{
    public function index(IndexTaxTypeRequest $request): JsonResponse
    {
        $paginator = TaxType::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new TaxTypeCollection($paginator));
    }

    public function store(StoreTaxTypeRequest $request): JsonResponse
    {
        $taxType = TaxType::query()->create($request->validated());
        
        return response()->json(new TaxTypeResource($taxType), 201);
    }

    public function update(UpdateTaxTypeRequest $request, TaxType $taxType): JsonResponse
    {
        $taxType->update($request->validated());
        
        return response()->json(new TaxTypeResource($taxType));
    }

    public function show(ShowTaxTypeRequest $request, TaxType $taxType): JsonResponse
    {
        return response()->json(new TaxTypeResource($taxType));
    }

    public function destroy(DestroyTaxTypeRequest $request, TaxType $taxType): JsonResponse
    {
        $taxType->delete();

        return response()->json(null, 204);
    }
}
