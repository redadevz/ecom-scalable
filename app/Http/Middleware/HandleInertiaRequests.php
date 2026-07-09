<?php

namespace App\Http\Middleware;

use App\Models\ItemCategory;
use App\Settings\ShopSettings;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default (storefront).
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'appName'    => config('app.name', 'Shop'),
            'currency'   => fn () => app(ShopSettings::class)->currency_symbol,
            'categories' => fn () => ItemCategory::where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name']),
            'cartCount'  => fn () => collect($request->session()->get('cart', []))->sum('quantity'),
            'flash'      => [
                'message' => fn () => $request->session()->get('message'),
                'error'   => fn () => $request->session()->get('error'),
            ],
        ];
    }
}
