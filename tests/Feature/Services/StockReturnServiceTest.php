<?php

namespace Tests\Feature\Services;

use App\Exceptions\StockReturnAlreadyProcessedException;
use App\Models\StockReturn;
use App\Models\StockReturnItem;
use App\Services\StockReturnService;

class StockReturnServiceTest extends ServiceTestCase
{
    private function scenario(int $stock = 10, int $qty = 3): array
    {
        $store = $this->makeStore();
        $item = $this->makeItem($store->id, ['current_stock_quantity' => $stock]);

        $return = StockReturn::factory()->create([
            'store_id' => $store->id,
            'exit_stock_time' => null,
        ]);
        StockReturnItem::factory()->create([
            'stock_return_id' => $return->id,
            'item_id' => $item->id,
            'quantity' => $qty,
        ]);

        return [$return, $item];
    }

    public function test_process_removes_stock_and_stamps_exit_time(): void
    {
        [$return, $item] = $this->scenario(stock: 10, qty: 3);

        app(StockReturnService::class)->process($return);

        $this->assertSame(7, (int) $item->fresh()->current_stock_quantity);
        $this->assertNotNull($return->fresh()->exit_stock_time);
    }

    public function test_process_twice_throws(): void
    {
        [$return] = $this->scenario();

        app(StockReturnService::class)->process($return);

        $this->expectException(StockReturnAlreadyProcessedException::class);
        app(StockReturnService::class)->process($return->fresh());
    }
}
