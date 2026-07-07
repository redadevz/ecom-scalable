<?php 

namespace App\Services;

use App\Exceptions\InsufficientStockException;
use App\Models\Item;
use App\Models\Price;
use App\Models\StockHistory;
use Illuminate\Support\Facades\DB;

class StockService{

  public function stockIn(Item $item, int $quantity, ?float $unitCost = null): StockHistory
    {
        return $this->move($item, $quantity, true, $unitCost);
    }


  public function stockOut(Item $item, int $quantity, ?float $unitCost = null): StockHistory
    {
        return $this->move($item, $quantity, false, $unitCost);
    }

  protected function move(Item $item, int $quantity, bool $isIn, ?float $unitCost = null): StockHistory
    {
        return DB::transaction(function () use ($item, $quantity, $isIn, $unitCost){
            $item = Item::whereKey($item->getKey())->lockForUpdate()->firstOrFail();

            $initialQty = $item->current_stock_quantity;
            $delta = $isIn ? $quantity : -$quantity;
            $newQty = $initialQty + $delta;


            if (! $isIn && $newQty < 0 && ! $this->negativeStockAllowed()) {
                throw new InsufficientStockException(
                    "Not enough stock for {$item->name}. Have {$initialQty}, need {$quantity}."
                );
            }

            // update the live quantity
            $item->current_stock_quantity = $newQty;
            $item->in_stock = $newQty > 0;
            $item->save();

            // cost snapshot: prior cost comes from the active price; the caller
            // may supply the actual cost of this movement (e.g. a purchase price)
            $priorCost = $this->currentCost($item);
            $newCost   = $unitCost ?? $priorCost;

            // write the audit row (before + after snapshot)
            return StockHistory::create([
                'store_id'               => $item->store_id,
                'item_id'                => $item->id,
                'initial_stock_quantity' => $initialQty,
                'initial_item_cost'      => $priorCost,
                'is_stock_entry'         => $isIn,
                'quantity'               => $quantity,
                'current_stock_quantity' => $newQty,
                'current_item_cost'      => $newCost,
            ]);
        });
    }

    /** The item's current unit cost, taken from its active price (0 if none). */
    protected function currentCost(Item $item): float
    {
        return (float) (Price::query()
            ->where('item_id', $item->id)
            ->active()
            ->latest('id')
            ->value('current_item_cost') ?? 0);
    }

    public function isStockable(?Item $item){

        return $item !== null && ! $item->is_service;
    }

    protected function negativeStockAllowed(): bool
    {
        return app(\App\Settings\ShopSettings::class)->negative_stock_allowed;
    }

}
?>