<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Dedicated permissions for the custom domain actions (confirm/receive/cancel/
 * invoice/process/apply/pay/refund). Created under the `craftable-pro` guard
 * and granted to the Administrator role. Idempotent.
 */
return new class extends Migration
{
    private array $permissions = [
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

    public function up(): void
    {
        $adminRoleId = DB::table('roles')
            ->where('name', 'Administrator')
            ->where('guard_name', 'craftable-pro')
            ->value('id');

        foreach ($this->permissions as $name) {
            $id = DB::table('permissions')
                ->where('name', $name)
                ->where('guard_name', 'craftable-pro')
                ->value('id');

            if (! $id) {
                $id = DB::table('permissions')->insertGetId([
                    'name' => $name,
                    'guard_name' => 'craftable-pro',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            if ($adminRoleId && ! DB::table('role_has_permissions')
                ->where('permission_id', $id)->where('role_id', $adminRoleId)->exists()) {
                DB::table('role_has_permissions')->insert([
                    'permission_id' => $id,
                    'role_id' => $adminRoleId,
                ]);
            }
        }

        app()['cache']->forget(config('permission.cache.key'));
    }

    public function down(): void
    {
        DB::table('permissions')
            ->whereIn('name', $this->permissions)
            ->where('guard_name', 'craftable-pro')
            ->delete();

        app()['cache']->forget(config('permission.cache.key'));
    }
};
