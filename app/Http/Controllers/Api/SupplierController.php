<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Supplier\DestroySupplierRequest;
use App\Http\Requests\Api\Supplier\IndexSupplierRequest;
use App\Http\Requests\Api\Supplier\ShowSupplierRequest;
use App\Http\Requests\Api\Supplier\StoreSupplierRequest;
use App\Http\Requests\Api\Supplier\UpdateSupplierRequest;
use App\Http\Resources\Api\SupplierCollection;
use App\Http\Resources\Api\SupplierResource;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;

class SupplierController extends Controller
{
    public function index(IndexSupplierRequest $request): JsonResponse
    {
        $paginator = Supplier::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new SupplierCollection($paginator));
    }

    public function store(StoreSupplierRequest $request): JsonResponse
    {
        $supplier = Supplier::query()->create($request->validated());
        
        return response()->json(new SupplierResource($supplier), 201);
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier): JsonResponse
    {
        $supplier->update($request->validated());
        
        return response()->json(new SupplierResource($supplier));
    }

    public function show(ShowSupplierRequest $request, Supplier $supplier): JsonResponse
    {
        return response()->json(new SupplierResource($supplier));
    }

    public function destroy(DestroySupplierRequest $request, Supplier $supplier): JsonResponse
    {
        $supplier->delete();

        return response()->json(null, 204);
    }
}
