<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\SaleReturnAlreadyProcessedException;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use Illuminate\Support\Facades\DB;

class SaleReturnService
{
    public function __construct(
        protected StockService $stock,
    ) {}

    /**
     * Process a customer return: returned goods go back into stock and the
     * original order lines are marked returned. Idempotent + concurrency-safe.
     */
    public function process(SaleReturn $return): SaleReturn
    {
        return DB::transaction(function () use ($return) {
            $return = SaleReturn::whereKey($return->getKey())->lockForUpdate()->firstOrFail();

            if ($return->entry_stock_time) {
                throw new SaleReturnAlreadyProcessedException("Sale return #{$return->id} has already been processed.");
            }

            $return->loadMissing('saleReturnItems.item', 'saleReturnItems.orderLine');

            foreach ($return->saleReturnItems as $line) {
                $this->restockLine($line);
            }

            // stamp processed time so it can't run twice
            $return->update(['entry_stock_time' => now()]);

            return $return->refresh();
        });
    }

    /**
     * Put one return line's goods back on the shelf and mark the order line returned.
     */
    protected function restockLine(SaleReturnItem $line): void
    {
        // skip missing items and services (no physical stock)
        if (! $line->item || $line->item->is_service) {
            return;
        }

        $qty = (int) $line->quantity;
        if ($qty <= 0) {
            return;
        }

        // goods come back onto the shelf
        $this->stock->stockIn($line->item, $qty);

        // mark the original order line as returned
        $line->orderLine?->update([
            'return_quantity' => (int) $line->orderLine->return_quantity + $qty,
            'return_time'     => now(),
            'return_required' => false,
        ]);
    }
}
