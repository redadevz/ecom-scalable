<?php

namespace App\Services;

use App\Models\OrderHeader;
use App\Models\OrderLine;
use App\Models\Price;
use RuntimeException;

class PricingService
{

    public function priceLine(OrderLine $line): array
    {
        $price = $this->activePrice($line->item_id);
        $qty = $line->quantity;

        $unitBeforeTax = $price->price_before_tax;
        $unitTax = $price->tax_value;
        $unitAfterTax = $price->price_after_tax;

        $lineBeforeTax = $this->round($unitBeforeTax * $qty);
        $lineTax = $this->round($unitTax * $qty);
        $lineAfterTax = $this->round($unitAfterTax * $qty);

        
        $discountValue = 0.0;
        $priceBeforeDiscount = $lineAfterTax;
        $priceAfterDiscount = $this->round($priceBeforeDiscount - $discountValue);

        return [
            'current_item_cost'     => $price->current_item_cost,
            'markup_percentage'     => $price->markup_percentage,
            'price_before_tax'      => $lineBeforeTax,
            'tax_value'             => $lineTax,
            'price_after_tax'       => $lineAfterTax,
            'price_before_discount' => $priceBeforeDiscount,
            'discount_value'        => $discountValue,
            'price_after_discount'  => $priceAfterDiscount,
            'price'                 => $priceAfterDiscount, 
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


    protected function activePrice(int $itemId): Price
    {
        $now = now();

        $price = Price::query()
            ->where('item_id', $itemId)
            ->active()
            ->latest('id')
            ->first();

        if (! $price) {
            throw new RuntimeException("No active price found for item #{$itemId}.");
        }

        return $price;
    }

    protected function round(float $value): float
    {
        return round($value, 3);
    }
}
