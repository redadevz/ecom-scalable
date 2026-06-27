<?php 

namespace App\Services;

use App\Models\Item;
use App\Models\StockHistory;
use Illuminate\Support\Facades\DB;

class StockService{

  public function stockIn(Item $item, int $quantity): StockHistory
    {
        return $this->move($item, $quantity, true);
    }


  public function stockOut(Item $item, int $quantity): StockHistory
    {
        return $this->move($item, $quantity, false);
    }

  protected function move(Item $item, int $quantity, bool $isIn): StockHistory
    {
        return DB::transaction(function () use ($item, $quantity, $isIn){
            $item = Item::whereKey($item->getKey())->lockForUpdate()->firstOrFail();

            $initialQty = $item->current_stock_quantity;
            $delta = $isIn ? $quantity : -$quantity;
            $newQty = $initialQty + $delta;


            if (! $isIn && $newQty < 0){
                
            }

        });
    }

}
?>