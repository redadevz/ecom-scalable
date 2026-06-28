<?php

namespace App\Services;

use App\Models\Discount;
use App\Models\Item;
use App\Models\Price;
use App\Models\Store;
use App\Models\Customer;
use Illuminate\Support\Collection;

class PricingService
{
    /**
     * Resolve the final unit price for an item in a given store context.
     * Returns an array with all price components for transparency.
     */
    public function resolve(Item $item, Store $store, ?Customer $customer = null, int $quantity = 1): array
    {
        $price = Price::where('item_id', $item->id)
            ->where('store_id', $store->id)
            ->where('is_active', true)
            ->latest()
            ->firstOrFail();

        $priceBeforeTax  = (float) $price->price_before_tax;
        $markupPct       = (float) $price->markup_percentage;
        $taxRate         = (float) optional($item->taxType)->percentage / 100;

        // Apply markup
        $afterMarkup     = $priceBeforeTax * (1 + $markupPct / 100);

        // Apply applicable discounts
        $discountValue   = $this->resolveDiscount($item, $store, $customer, $quantity, $afterMarkup);
        $discounted      = max(0, $afterMarkup - $discountValue);

        // Apply tax
        $priceAfterTax   = $this->round($discounted * (1 + $taxRate), $store);
        $taxValue        = $this->round($discounted * $taxRate, $store);

        return [
            'price_before_tax'    => $this->round($priceBeforeTax, $store),
            'markup_percentage'   => $markupPct,
            'price_after_markup'  => $this->round($afterMarkup, $store),
            'discount_value'      => $this->round($discountValue, $store),
            'price_before_tax_discounted' => $this->round($discounted, $store),
            'tax_percentage'      => $taxRate * 100,
            'tax_value'           => $taxValue,
            'price_after_tax'     => $priceAfterTax,
            'unit_price'          => $priceAfterTax,
            'line_total'          => $this->round($priceAfterTax * $quantity, $store),
        ];
    }

    /**
     * Find the best applicable discount for the item/customer/quantity combination.
     */
    public function resolveDiscount(Item $item, Store $store, ?Customer $customer, int $quantity, float $lineTotal): float
    {
        $discounts = Discount::where('store_id', $store->id)
            ->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('item_id')->orWhere('item_id', $item->id))
            ->where(fn ($q) => $q->whereNull('min_order_value')->orWhere('min_order_value', '<=', $lineTotal))
            ->get();

        if ($discounts->isEmpty()) {
            return 0.0;
        }

        // Pick the discount that yields the highest saving
        $best = 0.0;
        foreach ($discounts as $discount) {
            $saving = $discount->is_percentage
                ? $lineTotal * ($discount->percentage / 100)
                : (float) $discount->value;

            if ($discount->max_discount_value) {
                $saving = min($saving, (float) $discount->max_discount_value);
            }

            $best = max($best, $saving);
        }

        return $best;
    }

    /**
     * Round to the store's configured decimal places.
     */
    public function round(float $value, Store $store): float
    {
        $decimals = (int) ($store->decimal_places ?? 2);

        return match ($store->rounding_method ?? 'half_up') {
            'floor'   => floor($value * 10 ** $decimals) / 10 ** $decimals,
            'ceil'    => ceil($value  * 10 ** $decimals) / 10 ** $decimals,
            default   => round($value, $decimals),
        };
    }
}
