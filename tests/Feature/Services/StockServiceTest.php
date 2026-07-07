<?php

namespace Tests\Feature\Services;

use App\Exceptions\InsufficientStockException;
use App\Models\Item;
use App\Models\Price;
use App\Models\StockHistory;
use App\Services\StockService;
use App\Settings\ShopSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockServiceTest extends TestCase
{
    use RefreshDatabase;

    private function item(array $attrs = []): Item
    {
        return Item::factory()->create(array_merge([
            'is_service' => false,
            'current_stock_quantity' => 10,
        ], $attrs));
    }

    public function test_stock_in_increases_quantity_and_writes_history(): void
    {
        $item = $this->item(['current_stock_quantity' => 10]);

        $history = app(StockService::class)->stockIn($item, 5);

        $this->assertSame(15, (int) $item->fresh()->current_stock_quantity);
        $this->assertInstanceOf(StockHistory::class, $history);
        $this->assertTrue((bool) $history->is_stock_entry);
        $this->assertSame(10, (int) $history->initial_stock_quantity);
        $this->assertSame(15, (int) $history->current_stock_quantity);
    }

    public function test_stock_out_decreases_quantity(): void
    {
        $item = $this->item(['current_stock_quantity' => 10]);

        app(StockService::class)->stockOut($item, 3);

        $this->assertSame(7, (int) $item->fresh()->current_stock_quantity);
    }

    public function test_stock_out_beyond_available_throws_when_negative_not_allowed(): void
    {
        $settings = app(ShopSettings::class);
        $settings->negative_stock_allowed = false;
        $settings->save();

        $item = $this->item(['current_stock_quantity' => 5]);

        $this->expectException(InsufficientStockException::class);
        app(StockService::class)->stockOut($item, 100);
    }

    public function test_stock_out_beyond_available_allowed_when_setting_enabled(): void
    {
        $settings = app(ShopSettings::class);
        $settings->negative_stock_allowed = true;
        $settings->save();

        $item = $this->item(['current_stock_quantity' => 5]);

        app(StockService::class)->stockOut($item, 8);

        $this->assertSame(-3, (int) $item->fresh()->current_stock_quantity);
    }

    private function activePrice(Item $item, float $cost): void
    {
        Price::factory()->create([
            'item_id' => $item->id,
            'store_id' => $item->store_id,
            'is_active' => true,
            'start_time' => now()->subDay(),
            'end_time' => now()->addYear(),
            'current_item_cost' => $cost,
        ]);
    }

    public function test_stock_in_records_supplied_unit_cost(): void
    {
        $item = $this->item(['current_stock_quantity' => 10]);
        $this->activePrice($item, 7);

        $history = app(StockService::class)->stockIn($item, 5, 9.0);

        $this->assertSame(7.0, (float) $history->initial_item_cost); // prior cost from active price
        $this->assertSame(9.0, (float) $history->current_item_cost); // cost of this movement
    }

    public function test_stock_move_without_cost_carries_prior_cost(): void
    {
        $item = $this->item(['current_stock_quantity' => 10]);
        $this->activePrice($item, 6.5);

        $history = app(StockService::class)->stockOut($item, 2);

        $this->assertSame(6.5, (float) $history->initial_item_cost);
        $this->assertSame(6.5, (float) $history->current_item_cost);
    }

    public function test_is_stockable_excludes_services_and_nulls(): void
    {
        $service = app(StockService::class);

        $this->assertTrue($service->isStockable($this->item(['is_service' => false])));
        $this->assertFalse($service->isStockable($this->item(['is_service' => true])));
        $this->assertFalse($service->isStockable(null));
    }
}
