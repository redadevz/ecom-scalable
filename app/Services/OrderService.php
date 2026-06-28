<?php

namespace App\Services;

use App\Exceptions\InsufficientStockException;
use App\Exceptions\OrderException;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\Employee;
use App\Models\Item;
use App\Models\OrderDiscount;
use App\Models\OrderHeader;
use App\Models\OrderLine;
use App\Models\OrderLineDiscount;
use App\Models\OrderStatus;
use App\Models\OrderStatusHistory;
use App\Models\Store;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        protected PricingService $pricing,
        protected StockService   $stock,
    ) {}

    // ─── Creation ────────────────────────────────────────────────────────────

    /**
     * Open a new draft order for a customer on a specific store/channel.
     */
    public function create(
        Store    $store,
        Customer $customer,
        Employee $employee,
        array    $meta = [],          // sales_channel_id, delivery_type_id, loyalty_card_id …
    ): OrderHeader {
        return DB::transaction(function () use ($store, $customer, $employee, $meta) {
            $status = OrderStatus::where('name', 'Draft')->firstOrFail();

            $order = OrderHeader::create([
                'store_id'          => $store->id,
                'customer_id'       => $customer->id,
                'employee_id'       => $employee->id,
                'order_status_id'   => $status->id,
                'sales_channel_id'  => $meta['sales_channel_id']  ?? null,
                'delivery_type_id'  => $meta['delivery_type_id']  ?? null,
                'loyalty_card_id'   => $meta['loyalty_card_id']   ?? null,
                'payment_method_id' => $meta['payment_method_id'] ?? null,
                'shipment_address'  => $meta['shipment_address']  ?? null,
                'comments'          => $meta['comments']          ?? null,
                'is_active'         => true,
            ]);

            $this->recordStatusHistory($order, $status, $employee);

            return $order->load(['customer', 'store', 'status']);
        });
    }

    // ─── Lines ────────────────────────────────────────────────────────────────

    /**
     * Add or update a line on a draft order.
     * Recalculates price fresh every time.
     */
    public function addLine(OrderHeader $order, Item $item, int $quantity): OrderLine
    {
        $this->assertStatus($order, 'Draft');

        return DB::transaction(function () use ($order, $item, $quantity) {
            $priceData = $this->pricing->resolve($item, $order->store, $order->customer, $quantity);

            // Merge if item already on order, otherwise create
            $line = OrderLine::firstOrNew([
                'order_id' => $order->id,
                'item_id'  => $item->id,
            ]);

            $line->fill([
                'line_no'               => $line->line_no ?? ($order->lines()->max('line_no') + 1),
                'quantity'              => ($line->quantity ?? 0) + $quantity,
                'price_before_tax'      => $priceData['price_before_tax'],
                'price_after_tax'       => $priceData['price_after_tax'],
                'tax_value'             => $priceData['tax_value'],
                'total_discount_value'  => $priceData['discount_value'] * $quantity,
                'price_before_discount' => $priceData['price_after_markup'],
                'price_after_discount'  => $priceData['price_before_tax_discounted'],
                'price_adjustment'      => 0,
                'is_cancelled'          => false,
            ])->save();

            $this->recalculateTotals($order);

            return $line;
        });
    }

    /**
     * Remove a line from a draft order.
     */
    public function removeLine(OrderHeader $order, int $lineId): void
    {
        $this->assertStatus($order, 'Draft');

        DB::transaction(function () use ($order, $lineId) {
            $order->lines()->where('id', $lineId)->delete();
            $this->recalculateTotals($order);
        });
    }

    // ─── Discounts ────────────────────────────────────────────────────────────

    /**
     * Apply a header-level discount to the order (e.g. a coupon code).
     */
    public function applyDiscount(OrderHeader $order, Discount $discount): OrderDiscount
    {
        $this->assertStatus($order, 'Draft');

        return DB::transaction(function () use ($order, $discount) {
            $base    = (float) $order->order_total;
            $value   = $discount->is_percentage
                ? $base * ($discount->percentage / 100)
                : (float) $discount->value;

            if ($discount->max_discount_value) {
                $value = min($value, (float) $discount->max_discount_value);
            }

            $od = OrderDiscount::updateOrCreate(
                ['order_id' => $order->id, 'discount_id' => $discount->id],
                ['discount_value' => $value]
            );

            $this->recalculateTotals($order);

            return $od;
        });
    }

    // ─── Status transitions ───────────────────────────────────────────────────

    /**
     * Submit the draft → triggers stock reservation & invoice creation.
     */
    public function submit(OrderHeader $order, Employee $employee): OrderHeader
    {
        $this->assertStatus($order, 'Draft');
        $this->assertHasLines($order);

        return DB::transaction(function () use ($order, $employee) {
            // Reserve stock for every line
            foreach ($order->lines as $line) {
                $this->stock->stockOut($line->item, $line->quantity);
            }

            return $this->transition($order, 'Submitted', $employee);
        });
    }

    public function approve(OrderHeader $order, Employee $employee): OrderHeader
    {
        $this->assertStatus($order, 'Submitted');

        return DB::transaction(fn () => $this->transition($order, 'Approved', $employee));
    }

    public function schedule(OrderHeader $order, Employee $employee, \DateTimeInterface $shippedTime): OrderHeader
    {
        $this->assertStatus($order, 'Approved');
        $order->shipped_time = $shippedTime;
        $order->save();

        return DB::transaction(fn () => $this->transition($order, 'Scheduled', $employee));
    }

    public function markReady(OrderHeader $order, Employee $employee): OrderHeader
    {
        $this->assertStatus($order, 'Scheduled');

        return DB::transaction(fn () => $this->transition($order, 'Ready', $employee));
    }

    public function deliver(OrderHeader $order, Employee $employee): OrderHeader
    {
        $this->assertStatus($order, 'Ready');

        return DB::transaction(fn () => $this->transition($order, 'Delivered', $employee));
    }

    public function complete(OrderHeader $order, Employee $employee): OrderHeader
    {
        $this->assertStatus($order, 'Delivered');

        return DB::transaction(fn () => $this->transition($order, 'Completed', $employee));
    }

    /**
     * Cancel a non-completed order and release reserved stock.
     */
    public function cancel(OrderHeader $order, Employee $employee, string $reason = ''): OrderHeader
    {
        $nonCancellable = ['Completed', 'Cancelled'];
        if (in_array($order->status->name, $nonCancellable)) {
            throw new OrderException("Cannot cancel a {$order->status->name} order.");
        }

        return DB::transaction(function () use ($order, $employee, $reason) {
            // Release stock if it was already reserved
            $submittedOrLater = ['Submitted', 'Approved', 'Scheduled', 'Ready', 'Delivered'];
            if (in_array($order->status->name, $submittedOrLater)) {
                foreach ($order->lines as $line) {
                    $this->stock->stockIn($line->item, $line->quantity);
                }
            }

            $order->cancel_reason = $reason;
            $order->save();

            return $this->transition($order, 'Cancelled', $employee);
        });
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    protected function transition(OrderHeader $order, string $statusName, Employee $employee): OrderHeader
    {
        $status = OrderStatus::where('name', $statusName)->firstOrFail();
        $order->order_status_id = $status->id;
        $order->save();

        $this->recordStatusHistory($order, $status, $employee);

        return $order->refresh()->load(['status', 'lines']);
    }

    protected function recordStatusHistory(OrderHeader $order, OrderStatus $status, Employee $employee): void
    {
        OrderStatusHistory::create([
            'order_id'        => $order->id,
            'order_status_id' => $status->id,
            'employee_id'     => $employee->id,
            'start_time'      => now(),
        ]);
    }

    protected function recalculateTotals(OrderHeader $order): void
    {
        $order->refresh();
        $lines = $order->lines;

        $subtotal       = $lines->sum(fn ($l) => $l->price_before_tax  * $l->quantity);
        $taxTotal       = $lines->sum(fn ($l) => $l->tax_value          * $l->quantity);
        $discountTotal  = $lines->sum(fn ($l) => $l->total_discount_value);

        // Subtract header-level discounts
        $headerDiscount = $order->discounts()->sum('discount_value');

        $order->order_total         = max(0, ($subtotal + $taxTotal) - $discountTotal - $headerDiscount);
        $order->total_discount_value = $discountTotal + $headerDiscount;
        $order->save();
    }

    protected function assertStatus(OrderHeader $order, string $expected): void
    {
        if ($order->status->name !== $expected) {
            throw new OrderException(
                "Expected order status '{$expected}', got '{$order->status->name}'."
            );
        }
    }

    protected function assertHasLines(OrderHeader $order): void
    {
        if ($order->lines()->count() === 0) {
            throw new OrderException('Cannot submit an order with no lines.');
        }
    }
}
