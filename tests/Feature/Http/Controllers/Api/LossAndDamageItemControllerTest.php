<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\LossAndDamageItem;
use Brackets\CraftablePro\Models\CraftableProApiUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\LossAndDamageItemController
 */
final class LossAndDamageItemControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $requestData = [];

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.loss-and-damage-items.index"
        ]);
        $response = $this->getJson(route('craftable-pro-api.loss-and-damage-items.index'),
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }


    #[Test]
    public function store_behaves_as_expected(): void
    {
        $requestData = collect(LossAndDamageItem::factory()->make()->toArray())->map(fn($value) => $value instanceof \DateTime ? $value->format('Y-m-d H:i:s') : $value)->toArray();

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.loss-and-damage-items.store"
        ]);
        $response = $this->postJson(route('craftable-pro-api.loss-and-damage-items.store'),
            $requestData,
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }


    #[Test]
    public function update_behaves_as_expected(): void
    {
        $lossAndDamageItem = LossAndDamageItem::factory()->create();
        $requestData = collect(LossAndDamageItem::factory()->make()->toArray())->map(fn($value) => $value instanceof \DateTime ? $value->format('Y-m-d H:i:s') : $value)->toArray();

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.loss-and-damage-items.update"
        ]);
        $response = $this->putJson(route('craftable-pro-api.loss-and-damage-items.update', $lossAndDamageItem),
            $requestData,
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $lossAndDamageItem = LossAndDamageItem::factory()->create();
        $requestData = [];

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.loss-and-damage-items.show"
        ]);
        $response = $this->getJson(route('craftable-pro-api.loss-and-damage-items.show', $lossAndDamageItem),
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }


    #[Test]
    public function destroy_behaves_as_expected(): void
    {
        $lossAndDamageItem = LossAndDamageItem::factory()->create();
        $requestData = [];

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.loss-and-damage-items.destroy"
        ]);
        $response = $this->deleteJson(route('craftable-pro-api.loss-and-damage-items.destroy', $lossAndDamageItem),
            $requestData,
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }
}
