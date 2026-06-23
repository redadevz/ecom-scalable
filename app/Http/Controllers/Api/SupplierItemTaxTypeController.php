<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SupplierItemTaxType\DestroySupplierItemTaxTypeRequest;
use App\Http\Requests\Api\SupplierItemTaxType\IndexSupplierItemTaxTypeRequest;
use App\Http\Requests\Api\SupplierItemTaxType\ShowSupplierItemTaxTypeRequest;
use App\Http\Requests\Api\SupplierItemTaxType\StoreSupplierItemTaxTypeRequest;
use App\Http\Requests\Api\SupplierItemTaxType\UpdateSupplierItemTaxTypeRequest;
use App\Http\Resources\Api\SupplierItemTaxTypeCollection;
use App\Http\Resources\Api\SupplierItemTaxTypeResource;
use App\Models\SupplierItemTaxType;
use Illuminate\Http\JsonResponse;

class SupplierItemTaxTypeController extends Controller
{
    public function index(IndexSupplierItemTaxTypeRequest $request): JsonResponse
    {
        $paginator = SupplierItemTaxType::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new SupplierItemTaxTypeCollection($paginator));
    }

    public function store(StoreSupplierItemTaxTypeRequest $request): JsonResponse
    {
        $supplierItemTaxType = SupplierItemTaxType::query()->create($request->validated());
        
        return response()->json(new SupplierItemTaxTypeResource($supplierItemTaxType), 201);
    }

    public function update(UpdateSupplierItemTaxTypeRequest $request, SupplierItemTaxType $supplierItemTaxType): JsonResponse
    {
        $supplierItemTaxType->update($request->validated());
        
        return response()->json(new SupplierItemTaxTypeResource($supplierItemTaxType));
    }

    public function show(ShowSupplierItemTaxTypeRequest $request, SupplierItemTaxType $supplierItemTaxType): JsonResponse
    {
        return response()->json(new SupplierItemTaxTypeResource($supplierItemTaxType));
    }

    public function destroy(DestroySupplierItemTaxTypeRequest $request, SupplierItemTaxType $supplierItemTaxType): JsonResponse
    {
        $supplierItemTaxType->delete();

        return response()->json(null, 204);
    }
}
