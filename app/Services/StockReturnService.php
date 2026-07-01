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

            $return->load('stockReturnItems.item');

            foreach($return->stockReturnItems as $line){
                $this->processLine($line);
            }

            $return->update(['exit_stock_time' => now()]);

            return $return->refresh();
        });
    }

    protected function processLine(StockReturnItem $line){
        if(! $this->stock->isStockable($line->item)){
            return;
        }

        $qty = $line->quantity;
        if($qty <= 0){
            return;
        }

        $this->stock->stockOut($line->item, $qty);
    }
}