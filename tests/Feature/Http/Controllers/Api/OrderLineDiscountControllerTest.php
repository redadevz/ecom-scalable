<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\OrderLineDiscount;
use Brackets\CraftablePro\Models\CraftableProApiUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\OrderLineDiscountController
 */
final class OrderLineDiscountControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $requestData = [];

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.order-line-discounts.index"
        ]);
        $response = $this->getJson(route('craftable-pro-api.order-line-discounts.index'),
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }


    #[Test]
    public function store_behaves_as_expected(): void
    {
        $requestData = collect(OrderLineDiscount::factory()->make()->toArray())->map(fn($value) => $value instanceof \DateTime ? $value->format('Y-m-d H:i:s') : $value)->toArray();

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.order-line-discounts.store"
        ]);
        $response = $this->postJson(route('craftable-pro-api.order-line-discounts.store'),
            $requestData,
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }


    #[Test]
    public function update_behaves_as_expected(): void
    {
        $orderLineDiscount = OrderLineDiscount::factory()->create();
        $requestData = collect(OrderLineDiscount::factory()->make()->toArray())->map(fn($value) => $value instanceof \DateTime ? $value->format('Y-m-d H:i:s') : $value)->toArray();

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.order-line-discounts.update"
        ]);
        $response = $this->putJson(route('craftable-pro-api.order-line-discounts.update', $orderLineDiscount),
            $requestData,
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $orderLineDiscount = OrderLineDiscount::factory()->create();
        $requestData = [];

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.order-line-discounts.show"
        ]);
        $response = $this->getJson(route('craftable-pro-api.order-line-discounts.show', $orderLineDiscount),
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }


    #[Test]
    public function destroy_behaves_as_expected(): void
    {
        $orderLineDiscount = OrderLineDiscount::factory()->create();
        $requestData = [];

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.order-line-discounts.destroy"
        ]);
        $response = $this->deleteJson(route('craftable-pro-api.order-line-discounts.destroy', $orderLineDiscount),
            $requestData,
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }
}
