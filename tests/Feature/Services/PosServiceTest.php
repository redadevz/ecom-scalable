<?php

namespace Tests\Feature\Services;

use App\Exceptions\InsufficientStockException;
use App\Models\DeliveryType;
use App\Models\Invoice;
use App\Models\OrderHeader;
use App\Models\OrderStatus;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentTime;
use App\Models\SaleChannel;
use App\Services\PosService;

/**
 * PosService drives a counter sale through the full engine:
 * draft order/lines -> confirm (price + stock out) -> invoice -> pay,
 * with an optional manual whole-order discount.
 */
class PosServiceTest extends ServiceTestCase
{
    /**
     * Build the ambient lookups a POS checkout needs and return [store, item].
     * Item price_after_tax = 10 (from makeActivePrice).
     */
    private function scenario(int $stock = 10): array
    {
        $store = $this->makeStore();
        $item = $this->makeItem($store->id, ['current_stock_quantity' => $stock]);
        $this->makeActivePrice($item->id, $store->id);

        SaleChannel::factory()->create(['name' => 'In-store']);
        DeliveryType::factory()->create(['name' => 'Pickup']);
        PaymentTime::factory()->create(['name' => 'Immediate']);
        PaymentMethod::factory()->create(['sequence_no' => 1]);
        OrderStatus::factory()->create(['name' => 'Approved', 'is_default' => false]);

        return [$store, $item];
    }

    public function test_checkout_creates_confirmed_paid_order_and_removes_stock(): void
    {
        [, $item] = $this->scenario(stock: 10);

        $receipt = app(PosService::class)->checkout([
            'pay_now' => true,
            'lines'   => [['item_id' => $item->id, 'quantity' => 3]],
        ]);

        // receipt: 3 * 10 = 30, paid
        $this->assertSame(30.0, $receipt['total']);
        $this->assertTrue($receipt['paid']);
        $this->assertSame(3, $receipt['items']);

        // order confirmed + stock down
        $order = OrderHeader::where('order_no', $receipt['order_no'])->first();
        $this->assertNotNull($order);
        $this->assertTrue((bool) $order->is_approved);
        $this->assertSame(7, (int) $item->fresh()->current_stock_quantity);

        // invoice + payment exist and cover the total
        $invoice = Invoice::where('order_id', $order->id)->first();
        $this->assertNotNull($invoice);
        $this->assertTrue((bool) $invoice->is_paid);
        $this->assertSame(30.0, (float) Payment::where('invoice_id', $invoice->id)->sum('amount'));
    }

    public function test_checkout_applies_percentage_discount(): void
    {
        [, $item] = $this->scenario();

        $receipt = app(PosService::class)->checkout([
            'pay_now'  => true,
            'discount' => ['type' => 'percent', 'value' => 10],
            'lines'    => [['item_id' => $item->id, 'quantity' => 3]],
        ]);

        // 30 - 10% = 27
        $this->assertSame(27.0, $receipt['total']);
        $order = OrderHeader::where('order_no', $receipt['order_no'])->first();
        $this->assertSame(27.0, (float) $order->price);
        $this->assertSame(3.0, (float) $order->order_discount);
    }

    public function test_checkout_applies_fixed_amount_discount(): void
    {
        [, $item] = $this->scenario();

        $receipt = app(PosService::class)->checkout([
            'pay_now'  => true,
            'discount' => ['type' => 'amount', 'value' => 5],
            'lines'    => [['item_id' => $item->id, 'quantity' => 3]],
        ]);

        // 30 - 5 = 25
        $this->assertSame(25.0, $receipt['total']);
        $this->assertSame(25.0, (float) Payment::query()->sum('amount'));
    }

    public function test_discount_never_exceeds_the_total(): void
    {
        [, $item] = $this->scenario();

        $receipt = app(PosService::class)->checkout([
            'pay_now'  => true,
            'discount' => ['type' => 'amount', 'value' => 999],
            'lines'    => [['item_id' => $item->id, 'quantity' => 3]],
        ]);

        // clamped: total floors at 0, never negative
        $this->assertSame(0.0, $receipt['total']);
    }

    public function test_checkout_without_pay_now_leaves_invoice_unpaid(): void
    {
        [, $item] = $this->scenario();

        $receipt = app(PosService::class)->checkout([
            'pay_now' => false,
            'lines'   => [['item_id' => $item->id, 'quantity' => 2]],
        ]);

        $this->assertFalse($receipt['paid']);
        $order = OrderHeader::where('order_no', $receipt['order_no'])->first();
        $this->assertFalse((bool) Invoice::where('order_id', $order->id)->value('is_paid'));
        $this->assertSame(0, Payment::query()->count());
    }

    public function test_split_payments_covering_total_mark_invoice_paid(): void
    {
        [, $item] = $this->scenario();
        $card = PaymentMethod::factory()->create(['sequence_no' => 2])->id;
        $cash = PaymentMethod::query()->orderBy('id')->value('id');

        // total = 30, paid 20 + 10
        $receipt = app(PosService::class)->checkout([
            'lines'    => [['item_id' => $item->id, 'quantity' => 3]],
            'payments' => [
                ['payment_method_id' => $cash, 'amount' => 20],
                ['payment_method_id' => $card, 'amount' => 10],
            ],
        ]);

        $this->assertTrue($receipt['paid']);
        $this->assertSame(30.0, (float) Payment::query()->sum('amount'));
        $this->assertSame(2, Payment::query()->count());
    }

    public function test_partial_payment_leaves_invoice_unpaid(): void
    {
        [, $item] = $this->scenario();
        $cash = PaymentMethod::query()->orderBy('id')->value('id');

        // total = 30, only 10 paid
        $receipt = app(PosService::class)->checkout([
            'lines'    => [['item_id' => $item->id, 'quantity' => 3]],
            'payments' => [['payment_method_id' => $cash, 'amount' => 10]],
        ]);

        $this->assertFalse($receipt['paid']);
        $this->assertSame(10.0, (float) Payment::query()->sum('amount'));
    }

    public function test_checkout_blocks_when_stock_insufficient(): void
    {
        [, $item] = $this->scenario(stock: 2);

        $this->expectException(InsufficientStockException::class);

        app(PosService::class)->checkout([
            'pay_now' => true,
            'lines'   => [['item_id' => $item->id, 'quantity' => 5]],
        ]);
    }
}
