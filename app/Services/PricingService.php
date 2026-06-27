<?php

namespace App\Services;

use App\Models\OrderHeader;
use App\Models\OrderLine;
use App\Models\Price;
use RuntimeException;

class PricingService
{
    /**
     * Compute the money numbers for ONE order line.
     * Reads the item's active price (per unit) and multiplies by quantity.
     * Returns an array — it does NOT save. The caller decides to persist.
     */
    public function priceLine(OrderLine $line): array
    {
        $price = $this->activePrice($line->item_id);
        $qty = (float) $line->quantity;

        $unitBeforeTax = (float) $price->price_before_tax;
        $unitTax = (float) $price->tax_value;
        $unitAfterTax = (float) $price->price_after_tax;

        $lineBeforeTax = $this->round($unitBeforeTax * $qty);
        $lineTax = $this->round($unitTax * $qty);
        $lineAfterTax = $this->round($unitAfterTax * $qty);

        // discount is handled later by DiscountService — zero for now
        $discountValue = 0.0;
        $priceBeforeDiscount = $lineAfterTax;
        $priceAfterDiscount = $this->round($priceBeforeDiscount - $discountValue);

        return [
            'current_item_cost'     => (float) $price->current_item_cost,
            'markup_percentage'     => (float) $price->markup_percentage,
            'price_before_tax'      => $lineBeforeTax,
            'tax_value'             => $lineTax,
            'price_after_tax'       => $lineAfterTax,
            'price_before_discount' => $priceBeforeDiscount,
            'discount_value'        => $discountValue,
            'price_after_discount'  => $priceAfterDiscount,
            'price'                 => $priceAfterDiscount,   // final line total
        ];
    }

    /**
     * Compute the totals for a WHOLE order by summing its lines.
     * Returns the header numbers — again, does NOT save.
     */
    public function priceOrder(OrderHeader $order): array
    {
        $beforeTax = 0.0;
        $taxValue = 0.0;
        $afterTax = 0.0;
        $discount = 0.0;

        foreach ($order->orderLines as $line) {
            if ($line->is_canceled) {
                continue;   // skip canceled lines
            }

            $computed = $this->priceLine($line);

            $beforeTax += $computed['price_before_tax'];
            $taxValue  += $computed['tax_value'];
            $afterTax  += $computed['price_after_tax'];
            $discount  += $computed['discount_value'];
        }

        $afterDiscount = $this->round($afterTax - $discount);

        return [
            'price_before_tax'      => $this->round($beforeTax),
            'total_tax_value'       => $this->round($taxValue),
            'price_after_tax'       => $this->round($afterTax),
            'price_before_discount' => $this->round($afterTax),
            'total_discount_value'  => $this->round($discount),
            'price_after_discount'  => $afterDiscount,
            'price'                 => $afterDiscount,   // grand total
        ];
    }

    /**
     * Find the currently-active price for an item.
     * Active = is_active, and (if a window is set) today is inside it.
     */
    protected function activePrice(int $itemId): Price
    {
        $now = now();

        $price = Price::query()
            ->where('item_id', $itemId)
            ->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('start_time')->orWhere('start_time', '<=', $now))
            ->where(fn ($q) => $q->whereNull('end_time')->orWhere('end_time', '>=', $now))
            ->orderByDesc('id')   // newest active price wins
            ->first();

        if (! $price) {
            throw new RuntimeException("No active price found for item #{$itemId}.");
        }

        return $price;
    }

    /**
     * Money is decimal(15,3) in your schema — round to 3 places.
     */
    protected function round(float $value): float
    {
        return round($value, 3);
    }
}
