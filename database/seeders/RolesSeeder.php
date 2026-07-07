<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Business roles for the admin panel (guard: craftable-pro).
 *
 * Idempotent: creates each role if missing and re-syncs its permission set,
 * so it can be re-run any time (e.g. after adding new modules/permissions).
 *
 * Administrator is left untouched (it already owns every permission).
 *
 * Spec syntax per role:
 *   'craftable-pro'                 -> an exact permission name (panel access)
 *   'items'                         -> ALL actions of the "items" module
 *   'order-headers:index,create'    -> only the listed actions of a module
 */
class RolesSeeder extends Seeder
{
    private const GUARD = 'craftable-pro';

    /** Modules that count as "system config" — kept out of Store Manager. */
    private const SYSTEM_MODULES = [
        'role', 'permission', 'settings', 'languages', 'translation', 'craftable-pro-user',
    ];

    public function run(): void
    {
        $all = Permission::where('guard_name', self::GUARD)->pluck('name');

        $roles = [
            'Cashier'           => $this->cashier(),
            'Inventory Manager' => $this->inventoryManager(),
            'Accountant'        => $this->accountant(),
            'Store Manager'     => $this->storeManager($all),
        ];

        foreach ($roles as $name => $spec) {
            $role = Role::firstOrCreate(['name' => $name, 'guard_name' => self::GUARD]);
            $perms = $this->expand($spec, $all);
            $role->syncPermissions($perms);

            $this->command?->info(sprintf('  %-18s %3d permissions', $name, count($perms)));
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $this->command?->info('Roles synced.');
    }

    /** Front counter / point of sale. */
    private function cashier(): array
    {
        return [
            'craftable-pro',
            'order-headers:index,create,edit,confirm,invoice',
            'order-lines',
            'order-discounts:index,create,edit',
            'order-line-discounts:index,create,edit',
            'order-reviews:index,create',
            'order-statuses:index',
            'order-status-histories:index',
            'invoices:index,create,pay',
            'invoice-lines:index',
            'payments:index,create',
            'sale-returns:index,create,process,refund',
            'sale-return-items:index,create,edit',
            'refunds:index,create',
            'customers:index,create,edit',
            'loyalty-cards:index,create,edit',
            'loyalty-card-types:index',
            'items:index',
            'prices:index',
            'item-categories:index',
            'stock-histories:index',
            'sale-channels:index',
            'delivery-types:index',
            'shipments:index,create,edit',
            'payment-methods:index',
            'payment-terms:index',
            'payment-times:index',
            'measure-units:index',
            'discounts:index',
            'media:index,upload',
        ];
    }

    /** Purchasing, products, stock. */
    private function inventoryManager(): array
    {
        return [
            'craftable-pro',
            'items', 'prices', 'item-categories', 'item-tax-types', 'tax-types',
            'supplier-tax-types', 'supplier-item-tax-types', 'measure-units', 'bar-codes',
            'suppliers', 'purchases', 'purchase-items',
            'inventory-counts', 'inventory-count-items',
            'loss-and-damages', 'loss-and-damage-items',
            'stock-returns', 'stock-return-items', 'stock-histories',
            'discounts', 'discount-types', 'discount-schedules',
            'stores:index',
            'order-headers:index',
            'customers:index',
            'media:index,upload,destroy',
        ];
    }

    /** Invoicing, payments, refunds, financial documents. */
    private function accountant(): array
    {
        return [
            'craftable-pro',
            'invoices', 'invoice-lines', 'payments', 'refunds',
            'payment-methods', 'payment-terms', 'payment-times',
            'currencies',
            'tax-types:index', 'item-tax-types:index',
            'order-headers:index,invoice',
            'order-lines:index',
            'customers:index,edit',
            'suppliers:index',
            'purchases:index',
            'documents', 'document-types', 'document-categories',
            'media:index,upload',
        ];
    }

    /** All operational modules — everything except system config. */
    private function storeManager(Collection $all): array
    {
        return $all
            ->filter(fn ($name) => ! in_array($this->moduleOf($name), self::SYSTEM_MODULES, true))
            ->values()
            ->all();
    }

    /** Expand a role spec into a list of existing permission names. */
    private function expand(array $spec, Collection $all): array
    {
        $result = [];

        foreach ($spec as $entry) {
            if (str_contains($entry, ':')) {
                [$module, $actions] = explode(':', $entry, 2);
                foreach (explode(',', $actions) as $action) {
                    $result[] = "craftable-pro.{$module}.{$action}";
                }
            } elseif ($all->contains($entry)) {
                // Exact permission name (e.g. the "craftable-pro" panel-access permission).
                $result[] = $entry;
            } else {
                // Whole module: every permission under "craftable-pro.<module>.".
                $prefix = "craftable-pro.{$entry}.";
                foreach ($all as $name) {
                    if (str_starts_with($name, $prefix)) {
                        $result[] = $name;
                    }
                }
            }
        }

        // Keep only permissions that actually exist, de-duplicated.
        return array_values(array_intersect(array_unique($result), $all->all()));
    }

    /** The module segment of a permission name ("craftable-pro.items.index" -> "items"). */
    private function moduleOf(string $name): string
    {
        $parts = explode('.', $name);

        return $parts[1] ?? $name;
    }
}
