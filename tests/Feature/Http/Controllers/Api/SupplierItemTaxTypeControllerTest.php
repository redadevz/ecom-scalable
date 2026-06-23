<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\SupplierItemTaxType;
use Brackets\CraftablePro\Models\CraftableProApiUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\SupplierItemTaxTypeController
 */
final class SupplierItemTaxTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $requestData = [];

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.supplier-item-tax-types.index"
        ]);
        $response = $this->getJson(route('craftable-pro-api.supplier-item-tax-types.index'),
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }


    #[Test]
    public function store_behaves_as_expected(): void
    {
        $requestData = collect(SupplierItemTaxType::factory()->make()->toArray())->map(fn($value) => $value instanceof \DateTime ? $value->format('Y-m-d H:i:s') : $value)->toArray();

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.supplier-item-tax-types.store"
        ]);
        $response = $this->postJson(route('craftable-pro-api.supplier-item-tax-types.store'),
            $requestData,
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }


    #[Test]
    public function update_behaves_as_expected(): void
    {
        $supplierItemTaxType = SupplierItemTaxType::factory()->create();
        $requestData = collect(SupplierItemTaxType::factory()->make()->toArray())->map(fn($value) => $value instanceof \DateTime ? $value->format('Y-m-d H:i:s') : $value)->toArray();

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.supplier-item-tax-types.update"
        ]);
        $response = $this->putJson(route('craftable-pro-api.supplier-item-tax-types.update', $supplierItemTaxType),
            $requestData,
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $supplierItemTaxType = SupplierItemTaxType::factory()->create();
        $requestData = [];

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.supplier-item-tax-types.show"
        ]);
        $response = $this->getJson(route('craftable-pro-api.supplier-item-tax-types.show', $supplierItemTaxType),
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }


    #[Test]
    public function destroy_behaves_as_expected(): void
    {
        $supplierItemTaxType = SupplierItemTaxType::factory()->create();
        $requestData = [];

        /** @var CraftableProApiUser $user */
        $user = CraftableProApiUser::factory()->create();
        $user->givePermissionTo([
            "craftable-pro-api.supplier-item-tax-types.destroy"
        ]);
        $response = $this->deleteJson(route('craftable-pro-api.supplier-item-tax-types.destroy', $supplierItemTaxType),
            $requestData,
            ["Authorization" => "Bearer " . $user->createToken("test")->plainTextToken]
        )->assertSuccessful();
    }
}
