<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Refund;
use App\Models\SaleReturn;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PaymentService
{

    public function record(Invoice $invoice, float $amount, ?int $paymentMethodId = null, ?string $comments = null): Payment
    {
        if ($amount <= 0) {
            throw new RuntimeException('Payment amount must be greater than zero.');
        }

        return DB::transaction(function () use ($invoice, $amount, $paymentMethodId, $comments) {
            $invoice = Invoice::whereKey($invoice->getKey())->lockForUpdate()->firstOrFail();

            // payment_method_id is required — fall back to the first method
            $paymentMethodId ??= PaymentMethod::query()->orderBy('id')->value('id');

            $payment = Payment::create([
                'invoice_id'        => $invoice->id,
                'payment_method_id' => $paymentMethodId,
                'payment_no'        => $this->nextPaymentNo($invoice),
                'amount'            => $amount,
                'cash_paid'         => $amount,
                'cash_change'       => 0,
                'payment_time'      => now(),
                'comments'          => $comments,
            ]);

            // fully paid? mark the invoice
            $orderTotal = $invoice->order->price;
            $paidSoFar  = $invoice->payments()->sum('amount');

            if ($paidSoFar   >= $orderTotal) {
                $invoice->update(['is_paid' => true, 'payment_time' => now()]);
            }

            return $payment;
        });
    }

  
    public function settle(Invoice $invoice, ?int $paymentMethodId = null): Payment
    {
        $orderTotal = $invoice->order->price;
        $paidSoFar  = $invoice->payments()->sum('amount');
        $remaining  = round($orderTotal - $paidSoFar, 3);

        if ($remaining <= 0) {
            throw new RuntimeException("Invoice #{$invoice->invoice_no} is already fully paid.");
        }

        return $this->record($invoice, $remaining, $paymentMethodId);
    }


    public function refund(SaleReturn $return, float $amount, ?int $paymentMethodId = null, ?string $comments = null): Refund
    {
        if ($amount <= 0) {
            throw new RuntimeException('Refund amount must be greater than zero.');
        }

        return DB::transaction(function () use ($return, $amount, $paymentMethodId, $comments) {
            $return = SaleReturn::whereKey($return->getKey())->lockForUpdate()->firstOrFail();

            $paymentMethodId ??= PaymentMethod::query()->orderBy('id')->value('id');

            $refund = Refund::create([
                'sale_return_id'    => $return->id,
                'payment_method_id' => $paymentMethodId,
                'refund_no'         => 'REF-' . str_pad((string) $return->id, 5, '0', STR_PAD_LEFT),
                'amount'            => $amount,
                'cash_paid'         => $amount,
                'cash_change'       => 0,
                'refund_time'       => now(),
                'comments'          => $comments,
            ]);

            $return->update([
                'is_refunded'   => true,
                'refund_amount' => $amount,
                'refund_time'   => now(),
            ]);

            return $refund;
        });
    }

    protected function nextPaymentNo(Invoice $invoice): string
    {
        $seq = $invoice->payments()->count() + 1;
        return 'PAY-' . str_pad((string) $invoice->id, 5, '0', STR_PAD_LEFT) . '-' . $seq;
    }
}
