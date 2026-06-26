<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\LossAndDamage;
use Brackets\CraftablePro\Models\CraftableProApiUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\LossAndDamageController
 */
final class LossAndDamageControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $requestData = [];

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.loss-and-damages.index"
        ]);
        $response = $this->getJson(route('craftable-pro-api.loss-and-damages.index'),
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }


    #[Test]
    public function store_behaves_as_expected(): void
    {
        $requestData = collect(LossAndDamage::factory()->make()->toArray())->map(fn($value) => $value instanceof \DateTime ? $value->format('Y-m-d H:i:s') : $value)->toArray();

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.loss-and-damages.store"
        ]);
        $response = $this->postJson(route('craftable-pro-api.loss-and-damages.store'),
            $requestData,
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }


    #[Test]
    public function update_behaves_as_expected(): void
    {
        $lossAndDamage = LossAndDamage::factory()->create();
        $requestData = collect(LossAndDamage::factory()->make()->toArray())->map(fn($value) => $value instanceof \DateTime ? $value->format('Y-m-d H:i:s') : $value)->toArray();

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.loss-and-damages.update"
        ]);
        $response = $this->putJson(route('craftable-pro-api.loss-and-damages.update', $lossAndDamage),
            $requestData,
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $lossAndDamage = LossAndDamage::factory()->create();
        $requestData = [];

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.loss-and-damages.show"
        ]);
        $response = $this->getJson(route('craftable-pro-api.loss-and-damages.show', $lossAndDamage),
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }


    #[Test]
    public function destroy_behaves_as_expected(): void
    {
        $lossAndDamage = LossAndDamage::factory()->create();
        $requestData = [];

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.loss-and-damages.destroy"
        ]);
        $response = $this->deleteJson(route('craftable-pro-api.loss-and-damages.destroy', $lossAndDamage),
            $requestData,
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }
}
