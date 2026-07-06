<?php

namespace Tests\Feature\Services;

use App\Models\DeliveryType;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\MeasureUnit;
use App\Models\OrderHeader;
use App\Models\OrderLine;
use App\Models\PaymentMethod;
use App\Models\PaymentTime;
use App\Models\Price;
use App\Models\SaleChannel;
use App\Models\Store;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Shared helpers to build a minimal, valid commerce graph without tripping the
 * generated factories' unique constraints (one shared store per call, required
 * lookups created explicitly, optional FKs nulled).
 */
abstract class ServiceTestCase extends TestCase
{
    use RefreshDatabase;

    protected function makeStore(): Store
    {
        return Store::factory()->create();
    }

    protected function makeItem(?int $storeId = null, array $attrs = []): Item
    {
        $store = $storeId ? Store::find($storeId) : $this->makeStore();

        return Item::factory()->create(array_merge([
            'store_id' => $store->id,
            'item_category_id' => ItemCategory::factory()->create()->id,
            'supplier_id' => Supplier::factory()->create(['store_id' => $store->id, 'city_id' => $store->city_id])->id,
            'measure_unit_id' => MeasureUnit::factory()->create()->id,
            'is_service' => false,
            'current_stock_quantity' => 10,
        ], $attrs));
    }

    protected function makeActivePrice(int $itemId, int $storeId): Price
    {
        return Price::factory()->create([
            'item_id' => $itemId,
            'store_id' => $storeId,
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
    }

    protected function makeOrder(int $storeId, array $attrs = []): OrderHeader
    {
        return OrderHeader::factory()->create(array_merge([
            'store_id' => $storeId,
            'sale_channel_id' => SaleChannel::factory()->create()->id,
            'delivery_type_id' => DeliveryType::factory()->create()->id,
            'payment_method_id' => PaymentMethod::factory()->create(['sequence_no' => 1])->id,
            'payment_time_id' => PaymentTime::factory()->create()->id,
            'customer_id' => null,
            'loyalty_card_id' => null,
            'created_by' => null,
            'approved_by' => null,
            'managed_by' => null,
            'is_canceled' => false,
        ], $attrs));
    }

    protected function makeOrderLine(OrderHeader $order, int $itemId, array $attrs = []): OrderLine
    {
        return OrderLine::factory()->create(array_merge([
            'order_id' => $order->id,
            'store_id' => $order->store_id,
            'item_id' => $itemId,
            'quantity' => 1,
            'is_canceled' => false,
        ], $attrs));
    }
}
