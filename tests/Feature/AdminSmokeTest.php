<?php

namespace Tests\Feature;

use Brackets\CraftablePro\Models\CraftableProUser;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Smoke test: hit every admin index + create route as the admin and make
 * sure nothing throws a 5xx (catches bad eager-loads / relation typos from
 * the front-end restyle pass). Rendering is server-side (Inertia), so this
 * exercises the controllers, routes and props — not the Vue runtime.
 */
class AdminSmokeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Read-only smoke test: point at the real dev database (only GET requests).
        config(['database.connections.mysql.database' => 'laravel']);
        \Illuminate\Support\Facades\DB::purge('mysql');
    }

    public function test_all_admin_index_and_create_pages_load_without_server_error(): void
    {
        $admin = CraftableProUser::query()->orderBy('id')->first();
        if (! $admin) {
            $this->markTestSkipped('No admin user in the target database (seed the dev DB to run this smoke test).');
        }

        $names = collect(Route::getRoutes())
            ->filter(fn ($r) => in_array('GET', $r->methods()))
            ->map(fn ($r) => $r->getName())
            ->filter()
            ->filter(fn ($n) => str_starts_with($n, 'craftable-pro.')
                && (str_ends_with($n, '.index') || str_ends_with($n, '.create')))
            ->unique()
            ->values();

        // Skip routes whose URI needs a bound parameter (can't build a plain URL).
        $names = $names->reject(function ($name) {
            $uri = Route::getRoutes()->getByName($name)?->uri() ?? '';
            return str_contains($uri, '{');
        })->values();

        $this->assertNotEmpty($names, 'No admin routes found.');

        $failures = [];
        foreach ($names as $name) {
            try {
                $res = $this->actingAs($admin, 'craftable-pro')->get(route($name));
                if ($res->getStatusCode() >= 500) {
                    $failures[] = "$name -> {$res->getStatusCode()}";
                }
            } catch (\Throwable $e) {
                $failures[] = "$name -> EXCEPTION: " . $e->getMessage();
            }
        }

        $this->assertSame([], $failures, "Pages returning 5xx:\n" . implode("\n", $failures));
    }

    public function test_order_and_invoice_detail_pages_render(): void
    {
        $admin = CraftableProUser::query()->orderBy('id')->first();
        if (! $admin) {
            $this->markTestSkipped('No admin user in the target database.');
        }

        $checks = [
            'craftable-pro.order-headers.show' => \App\Models\OrderHeader::query()->value('id'),
            'craftable-pro.invoices.show' => \App\Models\Invoice::query()->value('id'),
        ];

        foreach ($checks as $route => $id) {
            if (! $id) {
                continue;
            }
            $res = $this->actingAs($admin, 'craftable-pro')->get(route($route, $id));
            $this->assertLessThan(500, $res->getStatusCode(), "$route returned {$res->getStatusCode()}");
        }
    }
}
