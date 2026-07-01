<?php 

namespace App\Services;

use App\Exceptions\LossAndDamageAlreadyProcessedException;
use App\Models\LossAndDamage;
use App\Models\LossAndDamageItem;
use Illuminate\Support\Facades\DB;

class LossAndDamageService{

    public function __construct(protected StockService $stock)
    {
    }

    public function apply(LossAndDamage $loss){
        return DB::transaction(function () use ($loss){
            $loss = LossAndDamage::whereKey($loss->getKey())->lockForUpdate()->firstOrFail();

            if($loss->exit_stock_time){
                throw new LossAndDamageAlreadyProcessedException("Loss/damage #{$loss->id} has already been processed.");
            }

            $loss->load('lossAndDamageItems.item');

            foreach($loss->lossAndDamageItems as $line){
                $this->writeOfLine($line);
            }

            $loss->update(['exit_stock_time' => now()]);
            return $loss->refresh();
        });
    }

    protected function writeOfLine(LossAndDamageItem $line){
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

?>