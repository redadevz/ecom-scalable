<?php 

namespace App\Services;


use App\Exceptions\OrderAlreadyInvoicedException;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\OrderHeader;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class InvoiceService{

    public function generate(OrderHeader $order){
        return DB::transaction(function () use ($order){
            $order = OrderHeader::whereKey($order->getKey())->lockForUpdate()->firstOrFail();

            if(! $order->is_approved){
                throw new RuntimeException("Order #{$order->order_no} must be confirmed before invoicing.");
            }

            if($order->invoices()->exists()){
                throw new OrderAlreadyInvoicedException("Order #{$order->order_no} is already invoiced.");
            }

            $order->load('orderLines');

            $invoice = Invoice::create([
                'order_id' => $order->id,
                'invoice_no' => Invoice::generateInvoiceNo($order->id),
                'is_paid' => false,
            ]);

            
            $lineNo = 1;
            foreach($order->orderLines as $line){
                if($line->is_canceled){
                    continue;
                }

                InvoiceLine::create([
                    'invoice_id' => $invoice->id,
                    'order_line_id' => $line->id,
                    'line_no' => $lineNo++,
                ]);
            }

            return $invoice->refresh();
        });

    }


}

?>