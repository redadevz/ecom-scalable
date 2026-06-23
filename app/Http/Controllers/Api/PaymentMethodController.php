<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PaymentMethod\DestroyPaymentMethodRequest;
use App\Http\Requests\Api\PaymentMethod\IndexPaymentMethodRequest;
use App\Http\Requests\Api\PaymentMethod\ShowPaymentMethodRequest;
use App\Http\Requests\Api\PaymentMethod\StorePaymentMethodRequest;
use App\Http\Requests\Api\PaymentMethod\UpdatePaymentMethodRequest;
use App\Http\Resources\Api\PaymentMethodCollection;
use App\Http\Resources\Api\PaymentMethodResource;
use App\Models\PaymentMethod;
use Illuminate\Http\JsonResponse;

class PaymentMethodController extends Controller
{
    public function index(IndexPaymentMethodRequest $request): JsonResponse
    {
        $paginator = PaymentMethod::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new PaymentMethodCollection($paginator));
    }

    public function store(StorePaymentMethodRequest $request): JsonResponse
    {
        $paymentMethod = PaymentMethod::query()->create($request->validated());
        
        return response()->json(new PaymentMethodResource($paymentMethod), 201);
    }

    public function update(UpdatePaymentMethodRequest $request, PaymentMethod $paymentMethod): JsonResponse
    {
        $paymentMethod->update($request->validated());
        
        return response()->json(new PaymentMethodResource($paymentMethod));
    }

    public function show(ShowPaymentMethodRequest $request, PaymentMethod $paymentMethod): JsonResponse
    {
        return response()->json(new PaymentMethodResource($paymentMethod));
    }

    public function destroy(DestroyPaymentMethodRequest $request, PaymentMethod $paymentMethod): JsonResponse
    {
        $paymentMethod->delete();

        return response()->json(null, 204);
    }
}
