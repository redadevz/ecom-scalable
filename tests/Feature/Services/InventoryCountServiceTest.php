<?php

namespace Tests\Feature\Services;

use App\Exceptions\InventoryCountAlreadyAppliedException;
use App\Models\InventoryCount;
use App\Models\InventoryCountItem;
use App\Services\InventoryCountService;

class InventoryCountServiceTest extends ServiceTestCase
{
    public function test_apply_adjusts_stock_up_and_down_to_counted(): void
    {
        $store = $this->makeStore();
        $short = $this->makeItem($store->id, ['current_stock_quantity' => 10]); // counted 8 → -2
        $over = $this->makeItem($store->id, ['current_stock_quantity' => 20]);  // counted 25 → +5

        $count = InventoryCount::factory()->create(['store_id' => $store->id, 'change_stock_time' => null]);
        InventoryCountItem::factory()->create(['inventory_count_id' => $count->id, 'item_id' => $short->id, 'quantity_counted' => 8]);
        InventoryCountItem::factory()->create(['inventory_count_id' => $count->id, 'item_id' => $over->id, 'quantity_counted' => 25]);

        app(InventoryCountService::class)->apply($count);

        $this->assertSame(8, (int) $short->fresh()->current_stock_quantity);
        $this->assertSame(25, (int) $over->fresh()->current_stock_quantity);
        $this->assertNotNull($count->fresh()->change_stock_time);
    }

    public function test_apply_twice_throws(): void
    {
        $store = $this->makeStore();
        $item = $this->makeItem($store->id, ['current_stock_quantity' => 10]);
        $count = InventoryCount::factory()->create(['store_id' => $store->id, 'change_stock_time' => null]);
        InventoryCountItem::factory()->create(['inventory_count_id' => $count->id, 'item_id' => $item->id, 'quantity_counted' => 9]);

        app(InventoryCountService::class)->apply($count);

        $this->expectException(InventoryCountAlreadyAppliedException::class);
        app(InventoryCountService::class)->apply($count->fresh());
    }
}
