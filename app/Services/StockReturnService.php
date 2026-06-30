<?php

namespace App\Services;

use App\Exceptions\StockReturnAlreadyProcessedException;
use App\Models\StockReturn;
use App\Models\StockReturnItem;
use Illuminate\Support\Facades\DB;

class StockReturnService{
    public function __construct(protected StockService $stock){

    }

    public function process(StockReturn $return){
        
        return DB::transaction(function () use ($return){
            $return = StockReturn::whereKey($return->getKey())->lockForUpdate()->firstOrFail();

            if($return->exit_stock_time){
                throw new StockReturnAlreadyProcessedException("Stock return #{$return->id} has already been processed.");
            }

            $return->loadMissing('stockReturnItems.item');

            foreach($return->stockReturnItems as $line){
                $this->processLine($line);
            }

            $return->update(['exit_stock_time' => now()]);

            return $return->refresh();
        });
    }

    protected function processLine(StockReturnItem $line){
        if(! $line->item || $line->item->is_service){
            return;
        }

        $qty = (int) $line->quantity;
        if($qty <= 0){
            return;
        }

        // goods leave your shelf, back to the supplier
        $this->stock->stockOut($line->item, $qty);
    }
}