<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PaymentTime\DestroyPaymentTimeRequest;
use App\Http\Requests\Api\PaymentTime\IndexPaymentTimeRequest;
use App\Http\Requests\Api\PaymentTime\ShowPaymentTimeRequest;
use App\Http\Requests\Api\PaymentTime\StorePaymentTimeRequest;
use App\Http\Requests\Api\PaymentTime\UpdatePaymentTimeRequest;
use App\Http\Resources\Api\PaymentTimeCollection;
use App\Http\Resources\Api\PaymentTimeResource;
use App\Models\PaymentTime;
use Illuminate\Http\JsonResponse;

class PaymentTimeController extends Controller
{
    public function index(IndexPaymentTimeRequest $request): JsonResponse
    {
        $paginator = PaymentTime::query()
            ->paginate($request->validated('per_page'), ['*'], $request->validated('page'));

        return response()->json(new PaymentTimeCollection($paginator));
    }

    public function store(StorePaymentTimeRequest $request): JsonResponse
    {
        $paymentTime = PaymentTime::query()->create($request->validated());
        
        return response()->json(new PaymentTimeResource($paymentTime), 201);
    }

    public function update(UpdatePaymentTimeRequest $request, PaymentTime $paymentTime): JsonResponse
    {
        $paymentTime->update($request->validated());
        
        return response()->json(new PaymentTimeResource($paymentTime));
    }

    public function show(ShowPaymentTimeRequest $request, PaymentTime $paymentTime): JsonResponse
    {
        return response()->json(new PaymentTimeResource($paymentTime));
    }

    public function destroy(DestroyPaymentTimeRequest $request, PaymentTime $paymentTime): JsonResponse
    {
        $paymentTime->delete();

        return response()->json(null, 204);
    }
}
