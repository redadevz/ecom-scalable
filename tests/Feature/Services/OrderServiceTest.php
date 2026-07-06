<?php

namespace Tests\Feature\Services;

use App\Models\DeliveryType;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\MeasureUnit;
use App\Models\OrderHeader;
use App\Models\OrderLine;
use App\Models\OrderStatus;
use App\Models\PaymentMethod;
use App\Models\PaymentTime;
use App\Models\Price;
use App\Models\SaleChannel;
use App\Models\Store;
use App\Models\Supplier;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    /** Build an item (stock 10) with an active price, an order and one line (qty 3). */
    private function scenario(int $stock = 10, int $qty = 3): array
    {
        // One shared store + one of each required lookup — keeps the graph
        // minimal so unique columns (code/name) never collide.
        $store = Store::factory()->create();
        $item = Item::factory()->create([
            'store_id' => $store->id,
            'item_category_id' => ItemCategory::factory()->create()->id,
            'supplier_id' => Supplier::factory()->create(['store_id' => $store->id, 'city_id' => $store->city_id])->id,
            'measure_unit_id' => MeasureUnit::factory()->create()->id,
            'is_service' => false,
            'current_stock_quantity' => $stock,
        ]);

        Price::factory()->create([
            'item_id' => $item->id,
            'store_id' => $store->id,
            'is_active' => true,
            'start_time' => now()->subDay(),
            'end_time' => now()->addYear(),
            'current_item_cost' => 5,
            'markup_percentage' => 0,
            'price_before_tax' => 8,
            'tax_value' => 2,
            'price_after_tax' => 10,
            'sale_price' => 10,
        ]);

        OrderStatus::factory()->create(['name' => 'Approved', 'is_default' => false]);
        OrderStatus::factory()->create(['name' => 'Cancelled', 'is_default' => false]);

        $order = OrderHeader::factory()->create([
            'store_id' => $store->id,
            'sale_channel_id' => SaleChannel::factory()->create()->id,
            'delivery_type_id' => DeliveryType::factory()->create()->id,
            'payment_method_id' => PaymentMethod::factory()->create(['sequence_no' => 1])->id,
            'payment_time_id' => PaymentTime::factory()->create()->id,
            'customer_id' => null,
            'loyalty_card_id' => null,
            'created_by' => null,
            'approved_by' => null,
            'managed_by' => null,
            'is_submitted' => false,
            'is_approved' => false,
            'is_canceled' => false,
        ]);

        OrderLine::factory()->create([
            'order_id' => $order->id,
            'store_id' => $store->id,
            'item_id' => $item->id,
            'quantity' => $qty,
            'is_canceled' => false,
        ]);

        return [$order, $item];
    }

    public function test_confirm_removes_stock_totals_and_approves(): void
    {
        [$order, $item] = $this->scenario(stock: 10, qty: 3);

        $confirmed = app(OrderService::class)->confirm($order);

        // 3 units left the shelf
        $this->assertSame(7, (int) $item->fresh()->current_stock_quantity);
        // approved + grand total = 3 * 10 (no discounts)
        $this->assertTrue((bool) $confirmed->is_approved);
        $this->assertSame(30.0, (float) $confirmed->price);
        $this->assertSame('Approved', $confirmed->latest_status);
    }

    public function test_cancel_returns_stock_for_approved_order(): void
    {
        [$order, $item] = $this->scenario(stock: 10, qty: 3);

        app(OrderService::class)->confirm($order);
        $this->assertSame(7, (int) $item->fresh()->current_stock_quantity);

        $canceled = app(OrderService::class)->cancel($order->fresh(), 'customer changed mind');

        $this->assertSame(10, (int) $item->fresh()->current_stock_quantity);
        $this->assertTrue((bool) $canceled->is_canceled);
        $this->assertSame('Cancelled', $canceled->latest_status);
    }

    public function test_cancel_twice_throws(): void
    {
        [$order] = $this->scenario();

        app(OrderService::class)->confirm($order);
        app(OrderService::class)->cancel($order->fresh());

        $this->expectException(\RuntimeException::class);
        app(OrderService::class)->cancel($order->fresh());
    }
}
