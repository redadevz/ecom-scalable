<?php

namespace App\Services;


use App\Models\OrderHeader;
use App\Models\OrderStatus;
use App\Models\OrderStatusHistory;
use Illuminate\Support\Facades\DB;

class OrderService{
    public function __construct(
        protected PricingService $pricing,
        protected DiscountService $discounts,
        protected StockService $stock,
    ){}

    public function confirm(OrderHeader $order){
        return DB::transaction(function () use ($order){
            $order->load('orderLines.item');

            $orderBeforeTax = 0.0;
            $orderTax = 0.0;
            $orderAfterTax = 0.0;
            $lineDiscounts = 0.0;

            // 1. price + discount each line, then save it
            foreach($order->orderLines as $line){
                if($line->is_canceled){
                    continue;
                }

                $numbers = $this->pricing->priceLine($line);
                $discount = $this->discounts->lineDiscount($line, $numbers['price_after_tax']);
                $afterDiscount = round($numbers['price_after_tax'] - $discount, 3);

                $line->update([
                    'current_item_cost' => $numbers['current_item_cost'],
                    'markup_percentage' => $numbers['markup_percentage'],
                    'price_before_tax' => $numbers['price_before_tax'],
                    'tax_value' => $numbers['tax_value'],
                    'price_after_tax' => $numbers['price_after_tax'],
                    'price_before_discount' => $numbers['price_after_tax'],
                    'discount_value' => $discount,
                    'price_after_discount' => $afterDiscount,
                    'price' => $afterDiscount,
                ]);

                $orderBeforeTax += $numbers['price_before_tax'];
                $orderTax += $numbers['tax_value'];
                $orderAfterTax += $numbers['price_after_tax'];
                $lineDiscounts += $discount;
            }

            // 2. order-level discount (on the after-line-discount total)
            $afterLineDiscounts = round($orderAfterTax - $lineDiscounts, 3);
            $orderLevelDiscount = $this->discounts->orderDiscount($order, $afterLineDiscounts);
            $totalDiscount = round($lineDiscounts + $orderLevelDiscount, 3);
            $grandTotal = round($orderAfterTax - $totalDiscount, 3);

            // 3. save the header totals + mark approved
            $order->update([
                'price_before_tax' => round($orderBeforeTax, 3),
                'total_tax_value' => round($orderTax, 3),
                'price_after_tax' => round($orderAfterTax, 3),
                'price_before_discount' => round($orderAfterTax, 3),
                'order_items_discount' => round($lineDiscounts, 3),
                'order_discount' => round($orderLevelDiscount, 3),
                'total_discount_value' => $totalDiscount,
                'price_after_discount' => $grandTotal,
                'price' => $grandTotal,
                'is_submitted' => true,
                'submitted_time' => now(),
                'is_approved' => true,
                'approved_time' => now(),
            ]);

            // 4. remove stock for each physical line
            foreach($order->orderLines as $line){
                if($line->is_canceled || $line->item->is_service){
                    continue;
                }
                $this->stock->stockOut($line->item, (int) $line->quantity);
            }

            // 5. record the status change
            $this->recordStatus($order);

            return $order->refresh();
        });
    }

    protected function recordStatus(OrderHeader $order, string $statusName = 'Approved'): void
    {
        $status = OrderStatus::where('name', $statusName)->first()
            ?? OrderStatus::where('is_default', true)->first();

        if(! $status){
            return;
        }

        OrderStatusHistory::where('order_id', $order->id)
            ->whereNull('end_time')
            ->update(['end_time' => now()]);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'order_status_id' => $status->id,
            'start_time' => now(),
        ]);

        $order->update([
            'latest_status' => $status->name,
            'latest_status_update' => now(),
        ]);
    }

    public function cancel(OrderHeader $order, string $reason='') {

        return DB::transaction(function () use ($order, $reason){
            if($order->is_canceled){
                throw new \RuntimeException("Order #{$order->id} is already canceled");
            }

            $order->load('orderLines.item');

            if($order->is_approved){
                foreach($order->orderLines as $line){
                    if($line->is_canceled || ! $line->item || $line->item->is_service){
                        continue;
                    }

                    $this->stock->stockIn($line->item, (int) $line->quantity);
                }
            }

            $order->update([
                'is_canceled'   => true,
                'canceled_time' => now(),
                'cancel_reason' => $reason,
            ]);

            $this->recordStatus($order, 'Cancelled');

            return $order->refresh();
        });
    }
}
