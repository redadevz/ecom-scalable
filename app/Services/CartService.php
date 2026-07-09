<?php

namespace App\Services;

use App\Models\Item;

/**
 * Session-based shopping cart for the storefront (guest-friendly).
 *
 * Stored as session('cart') = [ itemId => ['quantity' => n] ] — the same shape
 * HandleInertiaRequests reads for the header cart badge.
 */
class CartService
{
    private const KEY = 'cart';

    /** Raw session cart map. */
    public function raw(): array
    {
        return session(self::KEY, []);
    }

    public function add(int $itemId, int $qty = 1): void
    {
        $cart = $this->raw();
        $cart[$itemId]['quantity'] = ($cart[$itemId]['quantity'] ?? 0) + max(1, $qty);
        session([self::KEY => $cart]);
    }

    public function set(int $itemId, int $qty): void
    {
        $cart = $this->raw();
        if ($qty < 1) {
            unset($cart[$itemId]);
        } else {
            $cart[$itemId]['quantity'] = $qty;
        }
        session([self::KEY => $cart]);
    }

    public function remove(int $itemId): void
    {
        $cart = $this->raw();
        unset($cart[$itemId]);
        session([self::KEY => $cart]);
    }

    public function clear(): void
    {
        session()->forget(self::KEY);
    }

    /** Detailed, priced cart lines (drops items that are no longer sellable). */
    public function lines(): array
    {
        $cart = $this->raw();
        if (empty($cart)) {
            return [];
        }

        $items = Item::whereIn('id', array_keys($cart))
            ->where('is_active', true)
            ->with(['activePrice', 'media'])
            ->get()
            ->keyBy('id');

        $lines = [];
        foreach ($cart as $id => $row) {
            $item = $items->get($id);
            if (! $item || ! $item->activePrice) {
                continue; // no longer available
            }

            $qty  = (int) ($row['quantity'] ?? 0);
            if ($qty < 1) {
                continue;
            }

            $unit = (float) $item->activePrice->price_after_tax;
            $lines[] = [
                'item_id'    => $item->id,
                'name'       => $item->name,
                'sku'        => $item->sku_code,
                'image'      => $item->getFirstMediaUrl('images') ?: null,
                'unit_price' => $unit,
                'quantity'   => $qty,
                'line_total' => round($unit * $qty, 2),
                'in_stock'   => $item->is_service || $item->current_stock_quantity > 0,
                'is_service' => (bool) $item->is_service,
            ];
        }

        return $lines;
    }

    public function summary(): array
    {
        $lines    = $this->lines();
        $subtotal = array_sum(array_column($lines, 'line_total'));
        $count    = array_sum(array_column($lines, 'quantity'));

        return [
            'subtotal' => round($subtotal, 2),
            'total'    => round($subtotal, 2), // tax-inclusive pricing, in-store pickup
            'count'    => $count,
        ];
    }
}
