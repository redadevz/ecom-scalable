<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\DeliveryType;
use App\Models\Item;
use App\Models\OrderHeader;
use App\Models\OrderLine;
use App\Models\PaymentMethod;
use App\Models\PaymentTime;
use App\Models\SaleChannel;
use App\Models\Store;
use App\Services\InvoiceService;
use App\Services\OrderService;
use Brackets\CraftablePro\Models\CraftableProUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Realistic demo orders driven through the REAL business logic:
 *   draft -> OrderService@confirm (prices, discounts, totals, stock out)
 *         -> InvoiceService@generate (invoice + lines)
 *         -> some marked paid
 *
 * Idempotent: guards on is_approved / existing invoice, so re-running is safe.
 * Run after RetailDataSeeder (it needs the store, items, prices, lookups).
 */
class DemoOrdersSeeder extends Seeder
{
    public function run(): void
    {
        $store = Store::where('code', 'MAIN')->first();
        if (! $store) {
            $this->command?->warn('DemoOrdersSeeder: run RetailDataSeeder first.');
            return;
        }

        $orders   = app(OrderService::class);
        $invoices = app(InvoiceService::class);

        $adminId   = CraftableProUser::orderBy('id')->value('id') ?? 1;
        $customers = Customer::orderBy('id')->take(3)->get()->values();
        $items     = Item::orderBy('id')->get()->values();

        $lookups = [
            'sale_channel_id'   => SaleChannel::orderBy('id')->value('id'),
            'delivery_type_id'  => DeliveryType::orderBy('id')->value('id'),
            'payment_method_id' => PaymentMethod::orderBy('id')->value('id'),
            'payment_time_id'   => PaymentTime::orderBy('id')->value('id'),
        ];

        // [order_no, customerIndex, [[itemIndex, qty], ...], markPaid]
        $plan = [
            ['ORD-2001', 0, [[0, 3], [4, 2]],           true],
            ['ORD-2002', 1, [[2, 5], [6, 1], [10, 4]],  true],
            ['ORD-2003', 2, [[7, 2], [8, 3]],           false],
            ['ORD-2004', 0, [[12, 1], [13, 2]],         true],
            ['ORD-2005', 1, [[14, 4], [15, 2]],         false],
            ['ORD-2006', 2, [[1, 2], [3, 1], [5, 2]],   true],
        ];

        $confirmed = 0;
        $invoiced  = 0;

        foreach ($plan as [$no, $ci, $lines, $paid]) {
            $customer = $customers[$ci] ?? $customers->first();
            $order = $this->draft($store, $customer, $adminId, $no, $lookups, $items, $lines);

            // confirm (idempotent — skip if already approved)
            if (! $order->is_approved) {
                try {
                    $orders->confirm($order);
                    $confirmed++;
                } catch (\Throwable $e) {
                    $this->command?->warn("  {$no}: confirm skipped — {$e->getMessage()}");
                    continue;
                }
            }

            // invoice (idempotent — skip if already invoiced)
            $order = $order->fresh();
            if (! $order->invoices()->exists()) {
                $invoice = $invoices->generate($order);
                if ($paid) {
                    $invoice->update(['is_paid' => true, 'payment_time' => Carbon::now()]);
                }
                $invoiced++;
            }
        }

        $this->command?->info("DemoOrdersSeeder: {$confirmed} confirmed, {$invoiced} invoiced.");
    }

    private function draft(Store $store, Customer $customer, int $adminId, string $no, array $lookups, $items, array $lines): OrderHeader
    {
        $zeros = [
            'price_before_tax' => 0, 'total_tax_value' => 0, 'price_after_tax' => 0,
            'price_before_discount' => 0, 'order_items_discount' => 0, 'order_discount' => 0,
            'total_discount_value' => 0, 'price_after_discount' => 0, 'price' => 0,
        ];

        $order = OrderHeader::firstOrCreate(
            ['order_no' => $no],
            array_merge($zeros, $lookups, [
                'store_id'             => $store->id,
                'customer_id'          => $customer->id,
                'created_by'           => $adminId,
                'latest_status'        => 'Draft',
                'latest_status_update' => Carbon::now(),
                'is_submitted'         => false,
                'is_approved'          => false,
            ])
        );

        $lineZeros = [
            'current_item_cost' => 0, 'markup_percentage' => 0, 'price_before_tax' => 0,
            'tax_value' => 0, 'price_after_tax' => 0, 'price_before_discount' => 0,
            'discount_value' => 0, 'price_after_discount' => 0, 'price' => 0,
        ];

        foreach ($lines as $ln => [$itemIndex, $qty]) {
            OrderLine::firstOrCreate(
                ['order_id' => $order->id, 'item_id' => $items[$itemIndex]->id],
                array_merge($lineZeros, [
                    'store_id'    => $store->id,
                    'line_no'     => $ln + 1,
                    'quantity'    => $qty,
                    'is_canceled' => false,
                ])
            );
        }

        return $order;
    }
}
