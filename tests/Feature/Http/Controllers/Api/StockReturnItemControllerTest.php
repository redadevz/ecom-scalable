<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\StockReturnItem;
use Brackets\CraftablePro\Models\CraftableProApiUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\StockReturnItemController
 */
final class StockReturnItemControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $requestData = [];

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.stock-return-items.index"
        ]);
        $response = $this->getJson(route('craftable-pro-api.stock-return-items.index'),
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }


    #[Test]
    public function store_behaves_as_expected(): void
    {
        $requestData = collect(StockReturnItem::factory()->make()->toArray())->map(fn($value) => $value instanceof \DateTime ? $value->format('Y-m-d H:i:s') : $value)->toArray();

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.stock-return-items.store"
        ]);
        $response = $this->postJson(route('craftable-pro-api.stock-return-items.store'),
            $requestData,
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }


    #[Test]
    public function update_behaves_as_expected(): void
    {
        $stockReturnItem = StockReturnItem::factory()->create();
        $requestData = collect(StockReturnItem::factory()->make()->toArray())->map(fn($value) => $value instanceof \DateTime ? $value->format('Y-m-d H:i:s') : $value)->toArray();

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.stock-return-items.update"
        ]);
        $response = $this->putJson(route('craftable-pro-api.stock-return-items.update', $stockReturnItem),
            $requestData,
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $stockReturnItem = StockReturnItem::factory()->create();
        $requestData = [];

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.stock-return-items.show"
        ]);
        $response = $this->getJson(route('craftable-pro-api.stock-return-items.show', $stockReturnItem),
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }


    #[Test]
    public function destroy_behaves_as_expected(): void
    {
        $stockReturnItem = StockReturnItem::factory()->create();
        $requestData = [];

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.stock-return-items.destroy"
        ]);
        $response = $this->deleteJson(route('craftable-pro-api.stock-return-items.destroy', $stockReturnItem),
            $requestData,
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }
}
