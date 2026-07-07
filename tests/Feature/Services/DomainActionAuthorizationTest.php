<?php

namespace Tests\Feature\Services;

use Brackets\CraftablePro\Models\CraftableProUser;

class DomainActionAuthorizationTest extends ServiceTestCase
{
    private const ACTIONS = [
        'craftable-pro.order-headers.confirm',
        'craftable-pro.order-headers.cancel',
        'craftable-pro.order-headers.invoice',
        'craftable-pro.purchases.receive',
        'craftable-pro.sale-returns.process',
        'craftable-pro.sale-returns.refund',
        'craftable-pro.stock-returns.process',
        'craftable-pro.inventory-counts.apply',
        'craftable-pro.loss-and-damages.apply',
        'craftable-pro.invoices.pay',
    ];

    private function user(array $roles = []): CraftableProUser
    {
        $user = CraftableProUser::factory()->create();
        foreach ($roles as $role) {
            $user->assignRole($role);
        }

        return $user;
    }

    public function test_administrator_role_grants_all_domain_action_permissions(): void
    {
        $admin = $this->user(['Administrator']);

        foreach (self::ACTIONS as $permission) {
            $this->assertTrue($admin->can($permission), "Administrator should have $permission");
        }
    }

    public function test_user_without_role_has_no_domain_action_permissions(): void
    {
        $user = $this->user();

        foreach (self::ACTIONS as $permission) {
            $this->assertFalse($user->can($permission), "Role-less user must not have $permission");
        }
    }

    public function test_confirm_endpoint_is_forbidden_without_permission(): void
    {
        $order = $this->makeOrder($this->makeStore()->id);

        $this->actingAs($this->user(), 'craftable-pro')
            ->post(route('craftable-pro.order-headers.confirm', $order->id))
            ->assertForbidden();
    }
}
