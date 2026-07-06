<?php

namespace Tests\Feature\Services;

use App\Exceptions\OrderAlreadyInvoicedException;
use App\Models\InvoiceLine;
use App\Services\InvoiceService;
use RuntimeException;

class InvoiceServiceTest extends ServiceTestCase
{
    public function test_generate_creates_invoice_with_one_line_per_non_canceled_order_line(): void
    {
        $store = $this->makeStore();
        $item = $this->makeItem($store->id);
        $order = $this->makeOrder($store->id, ['is_approved' => true]);

        $this->makeOrderLine($order, $item->id);
        $this->makeOrderLine($order, $item->id);
        $this->makeOrderLine($order, $item->id, ['is_canceled' => true]); // skipped

        $invoice = app(InvoiceService::class)->generate($order);

        $this->assertFalse((bool) $invoice->is_paid);
        $this->assertSame('INV-' . str_pad((string) $order->id, 6, '0', STR_PAD_LEFT), $invoice->invoice_no);
        $this->assertSame(2, InvoiceLine::where('invoice_id', $invoice->id)->count());
    }

    public function test_generate_requires_approved_order(): void
    {
        $store = $this->makeStore();
        $order = $this->makeOrder($store->id, ['is_approved' => false]);

        $this->expectException(RuntimeException::class);
        app(InvoiceService::class)->generate($order);
    }

    public function test_generate_twice_throws(): void
    {
        $store = $this->makeStore();
        $item = $this->makeItem($store->id);
        $order = $this->makeOrder($store->id, ['is_approved' => true]);
        $this->makeOrderLine($order, $item->id);

        app(InvoiceService::class)->generate($order);

        $this->expectException(OrderAlreadyInvoicedException::class);
        app(InvoiceService::class)->generate($order->fresh());
    }
}
