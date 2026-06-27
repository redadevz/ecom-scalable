<?php 

namespace App\Services;

use App\Exceptions\InsufficientStockException;
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

            $initialQty = (float) $item->current_stock_quantity;
            $delta = $isIn ? $quantity : -$quantity;
            $newQty = $initialQty + $delta;

            // block negative stock on the way out
            if (! $isIn && $newQty < 0) {
                throw new InsufficientStockException(
                    "Not enough stock for {$item->name}. Have {$initialQty}, need {$quantity}."
                );
            }

            // update the live quantity
            $item->current_stock_quantity = $newQty;
            $item->in_stock = $newQty > 0;
            $item->save();

            // write the audit row (before + after snapshot)
            return StockHistory::create([
                'store_id'               => $item->store_id,
                'item_id'                => $item->id,
                'initial_stock_quantity' => $initialQty,
                'is_stock_entry'         => $isIn,
                'quantity'               => $quantity,
                'current_stock_quantity' => $newQty,
            ]);
        });
    }

}
?>