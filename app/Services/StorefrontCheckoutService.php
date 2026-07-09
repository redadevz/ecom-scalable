<?php

namespace App\Services;

use App\Models\City;
use App\Models\Customer;
use App\Models\DeliveryType;
use App\Models\OrderHeader;
use App\Models\OrderLine;
use App\Models\PaymentMethod;
use App\Models\PaymentTime;
use App\Models\SaleChannel;
use App\Models\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Places an online order from a storefront cart, driving the SAME engine as the
 * admin/POS: create customer → draft order/lines → OrderService@confirm
 * (price + stock-out + totals) → InvoiceService@generate. Payment is left open
 * (pay on pickup) — an online gateway is a later step.
 */
class StorefrontCheckoutService
{
    public function __construct(
        protected OrderService $orders,
        protected InvoiceService $invoices,
    ) {}

    /**
     * @param  array<array{item_id:int, quantity:int}>  $lines
     * @param  array{first_name:string,last_name:string,phone:string,email?:string,billing_address:string,delivery_type_id?:int,notes?:string}  $data
     * @return array{order_no:string,total:float,name:string,email:?string,items:int,delivery:string}
     */
    public function place(array $lines, array $data): array
    {
        $store = Store::orderBy('id')->first();
        if (! $store) {
            throw new RuntimeException('No store configured.');
        }

        return DB::transaction(function () use ($lines, $data, $store) {
            $customer = $this->customer($store, $data);

            $delivery = DeliveryType::find($data['delivery_type_id'] ?? null)
                ?? DeliveryType::where('name', 'Pickup')->first()
                ?? DeliveryType::orderBy('id')->first();

            $lookups = [
                'sale_channel_id'   => $this->refId(SaleChannel::class, 'Online'),
                'delivery_type_id'  => $delivery->id,
                'payment_time_id'   => $this->refId(PaymentTime::class, 'On Delivery'),
                'payment_method_id' => $this->refId(PaymentMethod::class, 'Cash'),
            ];

            $order = $this->draftOrder($store, $customer, $lookups, $data['notes'] ?? null);
            $this->draftLines($order, $store, $lines);

            $this->orders->confirm($order);
            $order = $order->fresh();

            $this->invoices->generate($order);

            return [
                'order_no' => $order->order_no,
                'total'    => round((float) $order->price, 2),
                'name'     => trim("{$customer->first_name} {$customer->last_name}"),
                'email'    => $customer->email,
                'items'    => (int) collect($lines)->sum('quantity'),
                'delivery' => $delivery->name,
            ];
        });
    }

    private function customer(Store $store, array $data): Customer
    {
        return Customer::create([
            'city_id'              => $store->city_id ?? City::orderBy('id')->value('id'),
            'created_at_store_id'  => $store->id,
            'code'                 => $this->nextCustomerCode(),
            'first_name'           => $data['first_name'],
            'last_name'            => $data['last_name'],
            'phone'                => $data['phone'],
            'email'                => $data['email'] ?? null,
            'billing_address'      => $data['billing_address'],
            'postal_code'          => $data['postal_code'] ?? null,
            'is_registered_online' => true,
            'is_active'            => true,
        ]);
    }

    private function draftOrder(Store $store, Customer $customer, array $lookups, ?string $notes): OrderHeader
    {
        $zeros = array_fill_keys([
            'price_before_tax', 'total_tax_value', 'price_after_tax', 'price_before_discount',
            'order_items_discount', 'order_discount', 'total_discount_value', 'price_after_discount', 'price',
        ], 0);

        return OrderHeader::create(array_merge($zeros, $lookups, [
            'store_id'             => $store->id,
            'customer_id'          => $customer->id,
            'customer_notes'       => $notes,
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
        $base = 'WEB-' . now()->format('ymd') . '-';
        $seq  = OrderHeader::where('order_no', 'like', $base . '%')->count() + 1;

        do {
            $candidate = $base . str_pad((string) $seq++, 4, '0', STR_PAD_LEFT);
        } while (OrderHeader::where('order_no', $candidate)->exists());

        return $candidate;
    }

    private function nextCustomerCode(): string
    {
        do {
            $code = 'WEB-' . strtoupper(Str::random(6));
        } while (Customer::where('code', $code)->exists());

        return $code;
    }

    private function refId(string $model, string $name): ?int
    {
        return $model::where('name', $name)->value('id') ?? $model::orderBy('id')->value('id');
    }
}
