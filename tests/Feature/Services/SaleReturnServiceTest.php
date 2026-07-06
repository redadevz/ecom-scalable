<?php

namespace Tests\Feature\Services;

use App\Exceptions\SaleReturnAlreadyProcessedException;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use App\Services\SaleReturnService;

class SaleReturnServiceTest extends ServiceTestCase
{
    private function scenario(int $stock = 10, int $returnQty = 2): array
    {
        $store = $this->makeStore();
        $item = $this->makeItem($store->id, ['current_stock_quantity' => $stock]);
        $order = $this->makeOrder($store->id, ['is_approved' => true]);
        $line = $this->makeOrderLine($order, $item->id, ['quantity' => 5, 'return_quantity' => 0]);

        $return = SaleReturn::factory()->create([
            'store_id' => $store->id,
            'order_id' => $order->id,
            'entry_stock_time' => null,
            'is_refunded' => false,
        ]);
        SaleReturnItem::factory()->create([
            'sale_return_id' => $return->id,
            'order_line_id' => $line->id,
            'item_id' => $item->id,
            'quantity' => $returnQty,
        ]);

        return [$return, $item, $line];
    }

    public function test_process_restocks_and_marks_order_line_returned(): void
    {
        [$return, $item, $line] = $this->scenario(stock: 10, returnQty: 2);

        app(SaleReturnService::class)->process($return);

        $this->assertSame(12, (int) $item->fresh()->current_stock_quantity);
        $this->assertSame(2, (int) $line->fresh()->return_quantity);
        $this->assertNotNull($return->fresh()->entry_stock_time);
    }

    public function test_process_twice_throws(): void
    {
        [$return] = $this->scenario();

        app(SaleReturnService::class)->process($return);

        $this->expectException(SaleReturnAlreadyProcessedException::class);
        app(SaleReturnService::class)->process($return->fresh());
    }
}
