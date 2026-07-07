<?php

namespace Database\Seeders;

use Brackets\CraftablePro\Models\CraftableProUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * One demo user per business role (guard: craftable-pro).
 *
 * Idempotent: matches on email, so re-running updates the same users
 * instead of duplicating. Run RolesSeeder first (DatabaseSeeder does).
 *
 * All demo passwords are "password".
 */
class UsersSeeder extends Seeder
{
    private const PASSWORD = 'password';

    public function run(): void
    {
        $users = [
            ['Sara',  'Manager',    'manager@shop.test',    'Store Manager'],
            ['Karim', 'Stock',      'inventory@shop.test',  'Inventory Manager'],
            ['Nadia', 'Counter',    'cashier@shop.test',    'Cashier'],
            ['Youssef','Finance',   'accountant@shop.test', 'Accountant'],
        ];

        foreach ($users as [$first, $last, $email, $role]) {
            $user = CraftableProUser::withTrashed()->updateOrCreate(
                ['email' => $email],
                [
                    'first_name'        => $first,
                    'last_name'         => $last,
                    'password'          => Hash::make(self::PASSWORD),
                    'locale'            => 'en',
                    'active'            => true,
                    'email_verified_at' => now(),
                    'deleted_at'        => null,
                ],
            );

            $user->syncRoles([$role]);

            $this->command?->info(sprintf('  %-22s %-18s (password: %s)', $email, $role, self::PASSWORD));
        }

        $this->command?->info('Demo users ready.');
    }
}
