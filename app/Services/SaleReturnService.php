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


    public function process(SaleReturn $return): SaleReturn
    {
        return DB::transaction(function () use ($return) {
            $return = SaleReturn::whereKey($return->getKey())->lockForUpdate()->firstOrFail();

            if ($return->entry_stock_time) {
                throw new SaleReturnAlreadyProcessedException("Sale return #{$return->id} has already been processed.");
            }

            $return->load('saleReturnItems.item', 'saleReturnItems.orderLine');

            foreach ($return->saleReturnItems as $line) {
                $this->restockLine($line);
            }

            $return->update(['entry_stock_time' => now()]);

            return $return->refresh();
        });
    }


    protected function restockLine(SaleReturnItem $line): void
    {
        if (! $line->item || $line->item->is_service) {
            return;
        }

        $qty = $line->quantity;
        if ($qty <= 0) {
            return;
        }

        $this->stock->stockIn($line->item, $qty);

        $line->orderLine?->update([
            'return_quantity' => $line->orderLine->return_quantity + $qty,
            'return_time'     => now(),
            'return_required' => false,
        ]);
    }
}
