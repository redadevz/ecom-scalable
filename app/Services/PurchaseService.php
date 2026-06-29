<?php 

namespace App\Services;

use App\Models\Purchase;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PurchaseService{

    public function __construct(protected StockService $stock)
    {

    }

    public function receive(Purchase $purchase){

        return DB::transaction(function () use ($purchase){
            if($purchase->entry_stock_time){
                throw new RuntimeException('Purchase #($purchace->id) has already been received.');
            }

            $purchase->load('purchaseItems.item');

            foreach($purchase->purchaseItems as $line){
                if(! $line->item || $line->item->is_service){
                    continue;
                }

                $this->stock->stockIn($line->item, $line->quantity);
            }

            $purchase->update(['entry_stock_time' => now()]);

            return $purchase->refresh();        
            
        }); 
    }
}

?>