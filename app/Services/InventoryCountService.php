<?php

declare(strict_types=1);

namespace App\Services;


use App\Exceptions\InventoryCountAlreadyAppliedException;
use App\Models\InventoryCount;
use App\Models\InventoryCountItem;
use Illuminate\Support\Facades\DB;

class InventoryCountService{
    public function __construct(protected StockService $stock)
    {

    }

    public function apply(InventoryCount $count){
        return DB::transaction(function () use ($count){
            $count = InventoryCount::whereKey($count->getKey())->lockForUpdate()->firstOrFail();

            if($count->change_stock_time){
                throw new InventoryCountAlreadyAppliedException();
            }

            $count->load('inventoryCountItems.item');

            foreach($count->inventoryCountItems as $line){
                $this->applyLine($line);
            }

            $count->update(['change_stock_time' => now()]);

            return $count->refresh();
        });
    }


    protected function applyLine(InventoryCountItem $line){
        if(! $this->stock->isStockable($line->item)){
            return;
        }

        $expected = $line->item->current_stock_quantity;
        $counted = $line->quantity_counted;
        $diff = $counted - $expected;

        $line->update(['quantity_change' => $diff, 'quantity_expected' => $expected]);

        if($diff > 0){
            $this->stock->stockIn($line->item, $diff);
            
        } elseif ($diff < 0){
            $this->stock->stockOut($line->item, abs($diff));
        }
    }
}

?>