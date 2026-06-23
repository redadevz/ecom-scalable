<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SupplierTaxType\DestroySupplierTaxTypeRequest;
use App\Http\Requests\Api\SupplierTaxType\IndexSupplierTaxTypeRequest;
use App\Http\Requests\Api\SupplierTaxType\ShowSupplierTaxTypeRequest;
use App\Http\Requests\Api\SupplierTaxType\StoreSupplierTaxTypeRequest;
use App\Http\Requests\Api\SupplierTaxType\UpdateSupplierTaxTypeRequest;
use App\Http\Resources\Api\SupplierTaxTypeCollection;
use App\Http\Resources\Api\SupplierTaxTypeResource;
use App\Models\SupplierTaxType;
use Illuminate\Http\JsonResponse;

class SupplierTaxTypeController extends Controller
{
    public function index(IndexSupplierTaxTypeRequest $request): JsonResponse
    {
        $paginator = SupplierTaxType::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new SupplierTaxTypeCollection($paginator));
    }

    public function store(StoreSupplierTaxTypeRequest $request): JsonResponse
    {
        $supplierTaxType = SupplierTaxType::query()->create($request->validated());
        
        return response()->json(new SupplierTaxTypeResource($supplierTaxType), 201);
    }

    public function update(UpdateSupplierTaxTypeRequest $request, SupplierTaxType $supplierTaxType): JsonResponse
    {
        $supplierTaxType->update($request->validated());
        
        return response()->json(new SupplierTaxTypeResource($supplierTaxType));
    }

    public function show(ShowSupplierTaxTypeRequest $request, SupplierTaxType $supplierTaxType): JsonResponse
    {
        return response()->json(new SupplierTaxTypeResource($supplierTaxType));
    }

    public function destroy(DestroySupplierTaxTypeRequest $request, SupplierTaxType $supplierTaxType): JsonResponse
    {
        $supplierTaxType->delete();

        return response()->json(null, 204);
    }
}
