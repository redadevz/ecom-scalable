<?php

namespace Tests\Feature\Services;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Services\PurchaseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

class PurchaseServiceTest extends TestCase
{
    use RefreshDatabase;

    private function purchaseWithLine(int $stock, int $qty): array
    {
        $item = Item::factory()->create(['is_service' => false, 'current_stock_quantity' => $stock]);
        $purchase = Purchase::factory()->create(['entry_stock_time' => null]);
        PurchaseItem::factory()->create([
            'purchase_id' => $purchase->id,
            'item_id' => $item->id,
            'quantity' => $qty,
        ]);

        return [$purchase, $item];
    }

    public function test_receive_adds_stock_and_stamps_entry_time(): void
    {
        [$purchase, $item] = $this->purchaseWithLine(stock: 10, qty: 5);

        app(PurchaseService::class)->receive($purchase);

        $this->assertSame(15, (int) $item->fresh()->current_stock_quantity);
        $this->assertNotNull($purchase->fresh()->entry_stock_time);
    }

    public function test_receive_is_idempotent(): void
    {
        [$purchase] = $this->purchaseWithLine(stock: 10, qty: 5);

        app(PurchaseService::class)->receive($purchase);

        $this->expectException(RuntimeException::class);
        app(PurchaseService::class)->receive($purchase->fresh());
    }

    public function test_receive_skips_service_items(): void
    {
        $service = Item::factory()->create(['is_service' => true, 'current_stock_quantity' => 0]);
        $purchase = Purchase::factory()->create(['entry_stock_time' => null]);
        PurchaseItem::factory()->create([
            'purchase_id' => $purchase->id,
            'item_id' => $service->id,
            'quantity' => 4,
        ]);

        app(PurchaseService::class)->receive($purchase);

        $this->assertSame(0, (int) $service->fresh()->current_stock_quantity);
    }
}
