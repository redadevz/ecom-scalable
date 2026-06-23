<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PaymentTerm\DestroyPaymentTermRequest;
use App\Http\Requests\Api\PaymentTerm\IndexPaymentTermRequest;
use App\Http\Requests\Api\PaymentTerm\ShowPaymentTermRequest;
use App\Http\Requests\Api\PaymentTerm\StorePaymentTermRequest;
use App\Http\Requests\Api\PaymentTerm\UpdatePaymentTermRequest;
use App\Http\Resources\Api\PaymentTermCollection;
use App\Http\Resources\Api\PaymentTermResource;
use App\Models\PaymentTerm;
use Illuminate\Http\JsonResponse;

class PaymentTermController extends Controller
{
    public function index(IndexPaymentTermRequest $request): JsonResponse
    {
        $paginator = PaymentTerm::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new PaymentTermCollection($paginator));
    }

    public function store(StorePaymentTermRequest $request): JsonResponse
    {
        $paymentTerm = PaymentTerm::query()->create($request->validated());
        
        return response()->json(new PaymentTermResource($paymentTerm), 201);
    }

    public function update(UpdatePaymentTermRequest $request, PaymentTerm $paymentTerm): JsonResponse
    {
        $paymentTerm->update($request->validated());
        
        return response()->json(new PaymentTermResource($paymentTerm));
    }

    public function show(ShowPaymentTermRequest $request, PaymentTerm $paymentTerm): JsonResponse
    {
        return response()->json(new PaymentTermResource($paymentTerm));
    }

    public function destroy(DestroyPaymentTermRequest $request, PaymentTerm $paymentTerm): JsonResponse
    {
        $paymentTerm->delete();

        return response()->json(null, 204);
    }
}
