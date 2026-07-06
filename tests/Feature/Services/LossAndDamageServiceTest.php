<?php

namespace Tests\Feature\Services;

use App\Exceptions\LossAndDamageAlreadyProcessedException;
use App\Models\LossAndDamage;
use App\Models\LossAndDamageItem;
use App\Services\LossAndDamageService;

class LossAndDamageServiceTest extends ServiceTestCase
{
    private function scenario(int $stock = 10, int $qty = 3): array
    {
        $store = $this->makeStore();
        $item = $this->makeItem($store->id, ['current_stock_quantity' => $stock]);

        $loss = LossAndDamage::factory()->create(['store_id' => $store->id, 'exit_stock_time' => null]);
        LossAndDamageItem::factory()->create([
            'loss_and_damage_id' => $loss->id,
            'item_id' => $item->id,
            'quantity' => $qty,
        ]);

        return [$loss, $item];
    }

    public function test_apply_writes_off_stock(): void
    {
        [$loss, $item] = $this->scenario(stock: 10, qty: 3);

        app(LossAndDamageService::class)->apply($loss);

        $this->assertSame(7, (int) $item->fresh()->current_stock_quantity);
        $this->assertNotNull($loss->fresh()->exit_stock_time);
    }

    public function test_apply_twice_throws(): void
    {
        [$loss] = $this->scenario();

        app(LossAndDamageService::class)->apply($loss);

        $this->expectException(LossAndDamageAlreadyProcessedException::class);
        app(LossAndDamageService::class)->apply($loss->fresh());
    }
}
