<?php

namespace Tests\Feature\Services;

use App\Models\DeliveryType;
use App\Models\Invoice;
use App\Models\OrderHeader;
use App\Models\PaymentMethod;
use App\Models\PaymentTime;
use App\Models\SaleChannel;
use App\Models\SaleReturn;
use App\Models\Store;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    private function order(float $price): OrderHeader
    {
        // ensure at least one payment method exists (service falls back to it)
        PaymentMethod::factory()->create(['sequence_no' => 1]);

        $store = Store::factory()->create();

        return OrderHeader::factory()->create([
            'store_id' => $store->id,
            'sale_channel_id' => SaleChannel::factory()->create()->id,
            'delivery_type_id' => DeliveryType::factory()->create()->id,
            'payment_method_id' => PaymentMethod::factory()->create(['sequence_no' => 2])->id,
            'payment_time_id' => PaymentTime::factory()->create()->id,
            'customer_id' => null,
            'loyalty_card_id' => null,
            'created_by' => null,
            'approved_by' => null,
            'managed_by' => null,
            'price' => $price,
            'is_canceled' => false,
        ]);
    }

    private function invoiceFor(OrderHeader $order): Invoice
    {
        return Invoice::factory()->create([
            'order_id' => $order->id,
            'is_paid' => false,
        ]);
    }

    public function test_settle_pays_remaining_and_marks_invoice_paid(): void
    {
        $invoice = $this->invoiceFor($this->order(30));

        $payment = app(PaymentService::class)->settle($invoice);

        $this->assertSame(30.0, (float) $payment->amount);
        $this->assertTrue((bool) $invoice->fresh()->is_paid);
    }

    public function test_partial_payments_only_mark_paid_when_total_reached(): void
    {
        $order = $this->order(30);
        $invoice = $this->invoiceFor($order);
        $service = app(PaymentService::class);

        $service->record($invoice, 10);
        $this->assertFalse((bool) $invoice->fresh()->is_paid);

        $service->record($invoice, 20);
        $this->assertTrue((bool) $invoice->fresh()->is_paid);
    }

    public function test_settle_throws_when_already_paid(): void
    {
        $invoice = $this->invoiceFor($this->order(30));
        $service = app(PaymentService::class);
        $service->settle($invoice);

        $this->expectException(RuntimeException::class);
        $service->settle($invoice->fresh());
    }

    public function test_record_rejects_non_positive_amount(): void
    {
        $invoice = $this->invoiceFor($this->order(30));

        $this->expectException(RuntimeException::class);
        app(PaymentService::class)->record($invoice, 0);
    }

    public function test_refund_creates_refund_and_flags_return(): void
    {
        PaymentMethod::factory()->create(['sequence_no' => 1]);
        $store = Store::factory()->create();
        $return = SaleReturn::factory()->create([
            'store_id' => $store->id,
            'is_refunded' => false,
        ]);

        $refund = app(PaymentService::class)->refund($return, 15);

        $this->assertSame(15.0, (float) $refund->amount);
        $this->assertTrue((bool) $return->fresh()->is_refunded);
        $this->assertSame(15.0, (float) $return->fresh()->refund_amount);
    }
}
