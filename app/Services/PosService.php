<?php

namespace App\Services;

use App\Models\DeliveryType;
use App\Models\OrderHeader;
use App\Models\OrderLine;
use App\Models\PaymentMethod;
use App\Models\PaymentTime;
use App\Models\SaleChannel;
use App\Models\Store;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Point-of-sale checkout — orchestrates a counter sale over the existing
 * engine: draft order/lines → confirm → invoice → (optional) payment.
 *
 * All-or-nothing: the whole sale commits in a single transaction. Throws plain
 * domain exceptions (RuntimeException / InsufficientStockException from the
 * inner services); the HTTP layer decides how to present them.
 */
class PosService
{
    /** Defaults for a walk-in, over-the-counter sale. */
    private const CHANNEL       = 'In-store';
    private const DELIVERY_TYPE = 'Pickup';
    private const PAYMENT_TIME  = 'Immediate';

    public function __construct(
        protected OrderService $orders,
        protected InvoiceService $invoices,
        protected PaymentService $payments,
    ) {}


    public function checkout(array $data, ?int $cashierId = null): array
    {
        $store = Store::orderBy('id')->first();
        if (! $store) {
            throw new RuntimeException('No store configured.');
        }

        $payNow  = (bool) ($data['pay_now'] ?? false);
        $lookups = $this->defaultLookups($data['payment_method_id'] ?? null);

        return DB::transaction(function () use ($data, $store, $cashierId, $lookups, $payNow) {
            $order = $this->draftOrder($store, $data['customer_id'] ?? null, $cashierId, $lookups);
            $this->draftLines($order, $store, $data['lines']);

            $this->orders->confirm($order);
            $order = $order->fresh();

            $invoice = $this->invoices->generate($order);

            $total = (float) $order->price;
            if ($payNow && $total > 0) {
                $this->payments->record($invoice, $total, $lookups['payment_method_id'], 'POS sale');
            }

            return [
                'order_no'   => $order->order_no,
                'invoice_no' => $invoice->invoice_no,
                'total'      => round($total, 2),
                'paid'       => $payNow,
                'items'      => (int) collect($data['lines'])->sum('quantity'),
            ];
        });
    }

    private function defaultLookups(?int $paymentMethodId): array
    {
        return [
            'sale_channel_id'   => $this->refId(SaleChannel::class, self::CHANNEL),
            'delivery_type_id'  => $this->refId(DeliveryType::class, self::DELIVERY_TYPE),
            'payment_time_id'   => $this->refId(PaymentTime::class, self::PAYMENT_TIME),
            'payment_method_id' => $paymentMethodId ?? PaymentMethod::orderBy('id')->value('id'),
        ];
    }

    private function draftOrder(Store $store, ?int $customerId, ?int $cashierId, array $lookups): OrderHeader
    {
        $zeros = array_fill_keys([
            'price_before_tax', 'total_tax_value', 'price_after_tax', 'price_before_discount',
            'order_items_discount', 'order_discount', 'total_discount_value', 'price_after_discount', 'price',
        ], 0);

        return OrderHeader::create(array_merge($zeros, $lookups, [
            'store_id'             => $store->id,
            'customer_id'          => $customerId,
            'created_by'           => $cashierId,
            'order_no'             => $this->nextOrderNo(),
            'latest_status'        => 'Draft',
            'latest_status_update' => now(),
            'is_submitted'         => false,
            'is_approved'          => false,
        ]));
    }

    private function draftLines(OrderHeader $order, Store $store, array $lines): void
    {
        $lineZeros = array_fill_keys([
            'current_item_cost', 'markup_percentage', 'price_before_tax', 'tax_value', 'price_after_tax',
            'price_before_discount', 'discount_value', 'price_after_discount', 'price',
        ], 0);

        
        $merged = collect($lines)
            ->groupBy('item_id')
            ->map(fn ($rows, $itemId) => ['item_id' => (int) $itemId, 'quantity' => (int) collect($rows)->sum('quantity')])
            ->values();

        $no = 1;
        foreach ($merged as $line) {
            OrderLine::create(array_merge($lineZeros, [
                'store_id'    => $store->id,
                'order_id'    => $order->id,
                'item_id'     => $line['item_id'],
                'line_no'     => (string) $no++,
                'quantity'    => $line['quantity'],
                'is_canceled' => false,
            ]));
        }
    }

    private function nextOrderNo(): string
    {
        $base = 'POS-' . now()->format('ymd') . '-';
        $seq  = OrderHeader::where('order_no', 'like', $base . '%')->count() + 1;

        do {
            $candidate = $base . str_pad((string) $seq++, 4, '0', STR_PAD_LEFT);
        } while (OrderHeader::where('order_no', $candidate)->exists());

        return $candidate;
    }

    private function refId(string $model, string $name): ?int
    {
        return $model::where('name', $name)->value('id') ?? $model::orderBy('id')->value('id');
    }
}
