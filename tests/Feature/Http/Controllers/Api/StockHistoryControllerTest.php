<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\StockHistory;
use Brackets\CraftablePro\Models\CraftableProApiUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\StockHistoryController
 */
final class StockHistoryControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $requestData = [];

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.stock-histories.index"
        ]);
        $response = $this->getJson(route('craftable-pro-api.stock-histories.index'),
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }


    #[Test]
    public function store_behaves_as_expected(): void
    {
        $requestData = collect(StockHistory::factory()->make()->toArray())->map(fn($value) => $value instanceof \DateTime ? $value->format('Y-m-d H:i:s') : $value)->toArray();

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.stock-histories.store"
        ]);
        $response = $this->postJson(route('craftable-pro-api.stock-histories.store'),
            $requestData,
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }


    #[Test]
    public function update_behaves_as_expected(): void
    {
        $stockHistory = StockHistory::factory()->create();
        $requestData = collect(StockHistory::factory()->make()->toArray())->map(fn($value) => $value instanceof \DateTime ? $value->format('Y-m-d H:i:s') : $value)->toArray();

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.stock-histories.update"
        ]);
        $response = $this->putJson(route('craftable-pro-api.stock-histories.update', $stockHistory),
            $requestData,
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $stockHistory = StockHistory::factory()->create();
        $requestData = [];

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.stock-histories.show"
        ]);
        $response = $this->getJson(route('craftable-pro-api.stock-histories.show', $stockHistory),
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }


    #[Test]
    public function destroy_behaves_as_expected(): void
    {
        $stockHistory = StockHistory::factory()->create();
        $requestData = [];

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.stock-histories.destroy"
        ]);
        $response = $this->deleteJson(route('craftable-pro-api.stock-histories.destroy', $stockHistory),
            $requestData,
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }
}
