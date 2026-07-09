<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Settings\ShopSettings;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Public storefront catalog — home, product listing (search + category filter),
 * and product detail. Only sellable items (active + active price) are shown.
 */
class CatalogController extends Controller
{
    public function home(ShopSettings $settings): Response
    {
        return Inertia::render('Shop/Home', [
            'featured' => $this->sellable()
                ->latest('id')
                ->take(10)
                ->get()
                ->map(fn (Item $i) => $this->card($i)),
            'newProducts' => $this->sellable()
                ->orderBy('name')
                ->take(8)
                ->get()
                ->map(fn (Item $i) => $this->card($i)),
            'categories' => \App\Models\ItemCategory::where('is_active', true)
                ->withCount(['items' => fn ($q) => $q->where('is_active', true)])
                ->orderBy('name')
                ->get(['id', 'name']),
            'currency' => $settings->currency_symbol,
        ]);
    }

    public function index(Request $request, ShopSettings $settings): Response
    {
        $q          = trim((string) $request->get('q', ''));
        $categoryId = $request->integer('category') ?: null;
        $sort       = in_array($request->get('sort'), ['newest', 'name', 'price_low', 'price_high'], true)
            ? $request->get('sort')
            : 'newest';

        // Correlated subquery: the active price of each item (for price sorting).
        $priceSub = \App\Models\Price::query()
            ->select('price_after_tax')
            ->whereColumn('prices.item_id', 'items.id')
            ->active()
            ->orderByDesc('id')
            ->limit(1);

        $products = $this->sellable()
            ->when($categoryId, fn (Builder $b) => $b->where('item_category_id', $categoryId))
            ->when($q !== '', fn (Builder $b) => $b->where(fn ($w) => $w
                ->where('name', 'like', "%{$q}%")
                ->orWhere('sku_code', 'like', "%{$q}%")))
            ->when($sort === 'name', fn (Builder $b) => $b->orderBy('name'))
            ->when($sort === 'newest', fn (Builder $b) => $b->latest('id'))
            ->when($sort === 'price_low', fn (Builder $b) => $b->orderBy($priceSub, 'asc'))
            ->when($sort === 'price_high', fn (Builder $b) => $b->orderByDesc($priceSub))
            ->paginate(12)
            ->withQueryString()
            ->through(fn (Item $i) => $this->card($i));

        return Inertia::render('Shop/Catalog', [
            'products'   => $products,
            'categories' => \App\Models\ItemCategory::where('is_active', true)
                ->withCount(['items' => fn ($x) => $x->where('is_active', true)])
                ->orderBy('name')
                ->get(['id', 'name']),
            'filters'  => ['q' => $q, 'category' => $categoryId, 'sort' => $sort],
            'currency' => $settings->currency_symbol,
        ]);
    }

    public function show(Item $item, ShopSettings $settings): Response
    {
        abort_unless($item->is_active && $item->activePrice, 404);

        $item->load(['itemCategory:id,name', 'media']);

        $related = $this->sellable()
            ->where('item_category_id', $item->item_category_id)
            ->whereKeyNot($item->id)
            ->take(4)
            ->get()
            ->map(fn (Item $i) => $this->card($i));

        return Inertia::render('Shop/Product', [
            'product' => [
                'id'          => $item->id,
                'name'        => $item->name,
                'sku'         => $item->sku_code,
                'description' => $item->description,
                'price'       => (float) $item->activePrice->price_after_tax,
                'image'       => $item->getFirstMediaUrl('images') ?: null,
                'category'    => $item->itemCategory?->name,
                'in_stock'    => $item->is_service || $item->current_stock_quantity > 0,
                'is_service'  => (bool) $item->is_service,
            ],
            'related'  => $related,
            'currency' => $settings->currency_symbol,
        ]);
    }

    /** Items a customer can actually buy: active, with an active price. */
    private function sellable(): Builder
    {
        return Item::query()
            ->where('is_active', true)
            ->whereHas('prices', fn ($p) => $p->active())
            ->with(['activePrice', 'media']);
    }

    private function card(Item $item): array
    {
        return [
            'id'         => $item->id,
            'name'       => $item->name,
            'sku'        => $item->sku_code,
            'price'      => (float) $item->activePrice->price_after_tax,
            'image'      => $item->getFirstMediaUrl('images') ?: null,
            'in_stock'   => $item->is_service || $item->current_stock_quantity > 0,
        ];
    }
}
