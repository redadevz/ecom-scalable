<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SaleChannel\DestroySaleChannelRequest;
use App\Http\Requests\Api\SaleChannel\IndexSaleChannelRequest;
use App\Http\Requests\Api\SaleChannel\ShowSaleChannelRequest;
use App\Http\Requests\Api\SaleChannel\StoreSaleChannelRequest;
use App\Http\Requests\Api\SaleChannel\UpdateSaleChannelRequest;
use App\Http\Resources\Api\SaleChannelCollection;
use App\Http\Resources\Api\SaleChannelResource;
use App\Models\SaleChannel;
use Illuminate\Http\JsonResponse;

class SaleChannelController extends Controller
{
    public function index(IndexSaleChannelRequest $request): JsonResponse
    {
        $paginator = SaleChannel::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new SaleChannelCollection($paginator));
    }

    public function store(StoreSaleChannelRequest $request): JsonResponse
    {
        $saleChannel = SaleChannel::query()->create($request->validated());
        
        return response()->json(new SaleChannelResource($saleChannel), 201);
    }

    public function update(UpdateSaleChannelRequest $request, SaleChannel $saleChannel): JsonResponse
    {
        $saleChannel->update($request->validated());
        
        return response()->json(new SaleChannelResource($saleChannel));
    }

    public function show(ShowSaleChannelRequest $request, SaleChannel $saleChannel): JsonResponse
    {
        return response()->json(new SaleChannelResource($saleChannel));
    }

    public function destroy(DestroySaleChannelRequest $request, SaleChannel $saleChannel): JsonResponse
    {
        $saleChannel->delete();

        return response()->json(null, 204);
    }
}
