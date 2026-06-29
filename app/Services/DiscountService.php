<?php 

namespace App\Services;

use App\Models\Discount;
use App\Models\DiscountType;
use App\Models\OrderHeader;
use App\Models\OrderLine;


class DiscountService {
    
    public function lineDiscount(OrderLine $line, $amount){
        $category_id = $line->item?->item_category_id;

        $discounts = Discount::query()
            ->where(fn ($q) => $q 
                ->where('item_id', $line->item_id)
                ->orWhere('item_category_id', $category_id))
            ->whereHas('discountType', fn ($q) => $q->active())
            ->with('discountType')
            ->get();
    }
}

?>