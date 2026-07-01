<?php

namespace Database\Seeders;

use App\Models\BarCode;
use App\Models\City;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\DeliveryType;
use App\Models\Discount;
use App\Models\DiscountType;
use App\Models\InventoryCount;
use App\Models\InventoryCountItem;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\ItemTaxType;
use App\Models\Language;
use App\Models\LossAndDamage;
use App\Models\LossAndDamageItem;
use App\Models\MeasureUnit;
use App\Models\OrderHeader;
use App\Models\OrderLine;
use App\Models\OrderStatus;
use App\Models\PaymentMethod;
use App\Models\PaymentTime;
use App\Models\Price;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Region;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use App\Models\StockReturn;
use App\Models\StockReturnItem;
use App\Models\SaleChannel;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\TaxType;
use App\Models\TimeZone;
use Brackets\CraftablePro\Models\CraftableProUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * A realistic, fully-wired retail dataset.
 *
 * Seeds a single store with a coherent catalog (items + active prices + stock),
 * customers, the order lifecycle statuses, lookups, discounts, and a few
 * ready-to-confirm draft orders so the business logic (OrderService->confirm)
 * can be exercised immediately.
 *
 * Idempotent: re-running updates rather than duplicating.
 */
class RetailDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = CraftableProUser::query()->orderBy('id')->first();
        $adminId = $admin?->id ?? 1;

        // --- Geography / currency / language --------------------------------
        $country  = Country::firstOrCreate(['name' => 'Morocco']);
        $region   = Region::firstOrCreate(['name' => 'Casablanca-Settat', 'country_id' => $country->id]);
        $tz       = TimeZone::firstOrCreate(['name' => 'Africa/Casablanca'], ['description' => 'Morocco Standard Time']);
        $city     = City::firstOrCreate(['name' => 'Casablanca', 'region_id' => $region->id], ['timezone_id' => $tz->id, 'zipcode' => '20000']);
        $currency = Currency::firstOrCreate(['short_name' => 'MAD'], ['name' => 'Moroccan Dirham', 'symbol' => 'DH', 'description' => 'MAD']);
        $language = Language::firstOrCreate(['short_name' => 'en'], ['name' => 'English', 'description' => 'English']);

        // --- Store ----------------------------------------------------------
        $store = Store::firstOrCreate(
            ['code' => 'MAIN'],
            [
                'city_id'           => $city->id,
                'language_id'       => $language->id,
                'currency_id'       => $currency->id,
                'name'              => 'Main Store',
                'is_active'         => true,
                'legal_entity_name' => 'Main Store SARL',
                'tax_code'          => 'TAX-001',
                'registration_number' => 'RC-100200',
                'address'           => '12 Boulevard Mohammed V, Casablanca',
                'phone'             => '+212 522 000 000',
                'email'             => 'contact@mainstore.ma',
            ]
        );

        // --- Measure units --------------------------------------------------
        $units = [];
        foreach ([
            ['Piece', 'pc'], ['Kilogram', 'kg'], ['Liter', 'L'], ['Pack', 'pk'], ['Box', 'box'],
        ] as [$name, $symbol]) {
            $units[$symbol] = MeasureUnit::firstOrCreate(['name' => $name], ['symbol' => $symbol]);
        }

        // --- Categories -----------------------------------------------------
        $categories = [];
        foreach (['Beverages', 'Snacks', 'Dairy', 'Bakery', 'Household', 'Produce'] as $name) {
            $categories[$name] = ItemCategory::firstOrCreate(['name' => $name], ['is_active' => true]);
        }

        // --- Tax types ------------------------------------------------------
        $vat20 = TaxType::firstOrCreate(
            ['code' => 'VAT20'],
            ['store_id' => $store->id, 'name' => 'VAT 20%', 'is_percentage' => true, 'value' => 20, 'start_time' => Carbon::now()->subDay(), 'is_active' => true]
        );
        TaxType::firstOrCreate(
            ['code' => 'VAT10'],
            ['store_id' => $store->id, 'name' => 'VAT 10%', 'is_percentage' => true, 'value' => 10, 'start_time' => Carbon::now()->subDay(), 'is_active' => true]
        );

        // --- Suppliers ------------------------------------------------------
        $suppliers = [];
        foreach ([
            ['SUP-ATLAS', 'Atlas Distribution', 'Youssef', 'Amrani'],
            ['SUP-SAHARA', 'Sahara Foods', 'Karim', 'Bennani'],
            ['SUP-MEDINA', 'Medina Wholesale', 'Fatima', 'El Idrissi'],
        ] as [$code, $company, $first, $last]) {
            $suppliers[$code] = Supplier::firstOrCreate(
                ['code' => $code],
                [
                    'store_id' => $store->id, 'city_id' => $city->id, 'created_by' => $adminId,
                    'first_name' => $first, 'last_name' => $last, 'is_company' => true,
                    'company_name' => $company, 'email' => strtolower(str_replace(' ', '', $company)) . '@supplier.ma',
                    'phone' => '+212 6' . random_int(10000000, 99999999),
                    'billing_address' => 'Zone Industrielle, Casablanca', 'is_active' => true,
                ]
            );
        }

        // --- Catalog: items + active price + barcode + tax link -------------
        // [name, category, unit, cost, markup%, stock, supplierCode]
        $catalog = [
            ['Mineral Water 1.5L',     'Beverages', 'pc', 2.50,  35, 240, 'SUP-ATLAS'],
            ['Orange Juice 1L',        'Beverages', 'pc', 7.00,  40, 120, 'SUP-ATLAS'],
            ['Cola Can 33cl',          'Beverages', 'pc', 3.20,  50, 300, 'SUP-ATLAS'],
            ['Mint Tea 250g',          'Beverages', 'pc', 12.00, 45, 80,  'SUP-MEDINA'],
            ['Potato Chips 150g',      'Snacks',    'pc', 4.50,  55, 160, 'SUP-SAHARA'],
            ['Salted Peanuts 200g',    'Snacks',    'pc', 6.00,  50, 90,  'SUP-SAHARA'],
            ['Chocolate Bar 100g',     'Snacks',    'pc', 5.50,  60, 140, 'SUP-SAHARA'],
            ['Fresh Milk 1L',          'Dairy',     'pc', 6.50,  25, 110, 'SUP-ATLAS'],
            ['Yogurt Pack x4',         'Dairy',     'pk', 9.00,  30, 70,  'SUP-ATLAS'],
            ['Gouda Cheese 250g',      'Dairy',     'pc', 18.00, 35, 45,  'SUP-MEDINA'],
            ['Whole Bread',            'Bakery',    'pc', 1.20,  60, 200, 'SUP-MEDINA'],
            ['Croissant',              'Bakery',    'pc', 1.80,  70, 150, 'SUP-MEDINA'],
            ['Dish Soap 500ml',        'Household', 'pc', 8.50,  45, 95,  'SUP-SAHARA'],
            ['Paper Towels x2',        'Household', 'pk', 11.00, 40, 60,  'SUP-SAHARA'],
            ['Tomatoes',               'Produce',   'kg', 4.00,  50, 130, 'SUP-MEDINA'],
            ['Bananas',                'Produce',   'kg', 6.50,  45, 100, 'SUP-MEDINA'],
        ];

        $items = [];
        foreach ($catalog as $i => [$name, $cat, $unit, $cost, $markup, $stock, $supCode]) {
            $sku = 'SKU-' . str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT);

            $item = Item::firstOrCreate(
                ['sku_code' => $sku],
                [
                    'store_id'               => $store->id,
                    'item_category_id'       => $categories[$cat]->id,
                    'supplier_id'            => $suppliers[$supCode]->id,
                    'measure_unit_id'        => $units[$unit]->id,
                    'name'                   => $name,
                    'is_service'             => false,
                    'in_stock'               => $stock > 0,
                    'using_default_quantity' => false,
                    'current_stock_quantity' => $stock,
                    'preferred_stock_quantity' => $stock,
                    'min_stock_quantity'     => 10,
                    'low_stock_warning'      => true,
                    'low_stock_quantity'     => 20,
                    'is_active'              => true,
                ]
            );
            $items[] = $item;

            // pricing math (cost -> markup -> +20% VAT)
            $priceBeforeTax = round($cost * (1 + $markup / 100), 3);
            $taxValue       = round($priceBeforeTax * 0.20, 3);
            $priceAfterTax  = round($priceBeforeTax + $taxValue, 3);

            Price::firstOrCreate(
                ['item_id' => $item->id, 'is_active' => true],
                [
                    'store_id'             => $store->id,
                    'created_by'           => $adminId,
                    'description'          => 'Standard retail price',
                    'current_item_cost'    => $cost,
                    'markup_percentage'    => $markup,
                    'price_before_tax'     => $priceBeforeTax,
                    'tax_value'            => $taxValue,
                    'price_after_tax'      => $priceAfterTax,
                    'sale_price'           => $priceAfterTax,
                    'price_change_allowed' => true,
                    'start_time'           => Carbon::now()->subDay(),
                    'end_time'             => null,
                ]
            );

            BarCode::firstOrCreate(
                ['bar_code' => '611' . str_pad((string) ($i + 1), 10, '0', STR_PAD_LEFT)],
                ['item_id' => $item->id, 'is_active' => true, 'description' => $name]
            );

            ItemTaxType::firstOrCreate(
                ['item_id' => $item->id, 'tax_type_id' => $vat20->id],
                ['start_time' => Carbon::now()->subDay(), 'description' => 'Standard VAT']
            );
        }

        // --- Customers ------------------------------------------------------
        $customers = [];
        foreach ([
            ['CUST-0001', 'Sara', 'Tahiri', 200.00],
            ['CUST-0002', 'Omar', 'Khalil', 0.00],
            ['CUST-0003', 'Leila', 'Saadi', 500.00],
        ] as [$code, $first, $last, $credit]) {
            $customers[] = Customer::firstOrCreate(
                ['code' => $code],
                [
                    'city_id' => $city->id, 'created_at_store_id' => $store->id, 'created_by' => $adminId,
                    'first_name' => $first, 'last_name' => $last, 'is_company' => false,
                    'email' => strtolower($first . '.' . $last) . '@example.ma',
                    'phone' => '+212 6' . random_int(10000000, 99999999),
                    'billing_address' => 'Quartier Maarif, Casablanca',
                    'credit' => $credit, 'is_active' => true,
                ]
            );
        }

        // --- Order lifecycle statuses --------------------------------------
        $statusNames = ['Draft', 'Submitted', 'Approved', 'Scheduled', 'Ready', 'Delivered', 'Completed', 'Cancelled'];
        foreach ($statusNames as $idx => $name) {
            OrderStatus::firstOrCreate(['name' => $name], ['is_default' => $name === 'Draft']);
        }

        // --- Order lookups --------------------------------------------------
        foreach (['In-store', 'Online', 'Phone'] as $name) {
            SaleChannel::firstOrCreate(['name' => $name], ['is_active' => true]);
        }
        foreach (['Pickup', 'Home Delivery'] as $name) {
            DeliveryType::firstOrCreate(['name' => $name], ['is_active' => true]);
        }
        foreach ([['Cash', 'CASH', 1], ['Card', 'CARD', 2], ['Store Credit', 'CREDIT', 3]] as [$name, $code, $seq]) {
            PaymentMethod::firstOrCreate(['code' => $code], ['name' => $name, 'sequence_no' => $seq, 'is_active' => true]);
        }
        foreach (['Immediate', 'On Delivery', 'Net 30'] as $name) {
            PaymentTime::firstOrCreate(['name' => $name], ['is_active' => true]);
        }

        // --- Discounts ------------------------------------------------------
        $storeWide = DiscountType::firstOrCreate(
            ['name' => 'Store-wide 10%'],
            [
                'store_id' => $store->id, 'is_percentage' => true, 'value' => 10,
                'min_order_value' => 200, 'apply_to_all' => true, 'max_discount_value' => 100,
                'start_time' => Carbon::now()->subDay(), 'is_active' => true,
            ]
        );
        $dairyDeal = DiscountType::firstOrCreate(
            ['name' => 'Dairy 15%'],
            [
                'store_id' => $store->id, 'is_percentage' => true, 'value' => 15,
                'apply_to_all' => false, 'start_time' => Carbon::now()->subDay(), 'is_active' => true,
            ]
        );
        Discount::firstOrCreate(
            ['discount_type_id' => $dairyDeal->id, 'item_category_id' => $categories['Dairy']->id],
            ['description' => '15% off all dairy products']
        );

        // --- A few ready-to-confirm DRAFT orders ----------------------------
        $lookups = [
            'sale_channel_id'   => SaleChannel::query()->orderBy('id')->value('id'),
            'delivery_type_id'  => DeliveryType::query()->orderBy('id')->value('id'),
            'payment_method_id' => PaymentMethod::query()->orderBy('id')->value('id'),
            'payment_time_id'   => PaymentTime::query()->orderBy('id')->value('id'),
        ];

        $this->seedDraftOrder($store, $customers[0], $adminId, 'ORD-1001', $lookups, [
            [$items[0], 6], [$items[4], 3], [$items[10], 4],
        ]);
        $this->seedDraftOrder($store, $customers[2], $adminId, 'ORD-1002', $lookups, [
            [$items[7], 4], [$items[8], 2], [$items[9], 1],   // dairy-heavy -> triggers Dairy 15%
        ]);

        // --- An UNRECEIVED purchase (ready to receive into stock) -----------
        $purchase = Purchase::firstOrCreate(
            ['store_id' => $store->id, 'supplier_id' => $suppliers['SUP-ATLAS']->id, 'description' => 'Weekly restock — Atlas'],
            ['is_paid' => false]   // entry_stock_time left null = not yet received
        );

        foreach ([[$items[0], 100], [$items[1], 50], [$items[2], 120]] as [$item, $qty]) {
            $cost = (float) $item->prices()->where('is_active', true)->value('current_item_cost');
            $tax  = round($cost * 0.20, 3);

            PurchaseItem::firstOrCreate(
                ['purchase_id' => $purchase->id, 'item_id' => $item->id],
                [
                    'supplier_price_before_tax' => $cost,
                    'supplier_tax_value'        => $tax,
                    'supplier_price_after_tax'  => round($cost + $tax, 3),
                    'supplier_discount_value'   => 0,
                    'quantity'                  => $qty,
                    'return_amount'             => 0,
                ]
            );
        }

        // --- An UNPROCESSED sale return (customer returns 2x Fresh Milk) -----
        $returnOrder = OrderHeader::with('orderLines')->where('order_no', 'ORD-1002')->first();
        $milkLine = $returnOrder?->orderLines->firstWhere('item_id', $items[7]->id); // Fresh Milk

        if ($milkLine) {
            $saleReturn = SaleReturn::firstOrCreate(
                ['store_id' => $store->id, 'order_id' => $returnOrder->id, 'description' => 'Customer returned 2x Fresh Milk'],
                ['is_refund_required' => true, 'is_refunded' => false]   // entry_stock_time null = not yet processed
            );

            SaleReturnItem::firstOrCreate(
                ['sale_return_id' => $saleReturn->id, 'order_line_id' => $milkLine->id],
                [
                    'item_id'         => $items[7]->id,
                    'line_no'         => 1,
                    'quantity'        => 2,
                    'return_quantity' => 2,
                ]
            );
        }

        // --- An UNPROCESSED stock return (send 10x Cola Can back to supplier) -
        $stockReturn = StockReturn::firstOrCreate(
            ['store_id' => $store->id, 'purchase_id' => $purchase->id, 'description' => 'Returned 10x Cola Can to supplier'],
            ['is_paid' => false]   // exit_stock_time null = not yet processed
        );

        $colaCost = (float) $items[2]->prices()->where('is_active', true)->value('current_item_cost');
        $colaTax  = round($colaCost * 0.20, 3);

        StockReturnItem::firstOrCreate(
            ['stock_return_id' => $stockReturn->id, 'item_id' => $items[2]->id],   // Cola Can
            [
                'quantity'                  => 10,
                'supplier_price_before_tax' => $colaCost,
                'supplier_tax_value'        => $colaTax,
                'supplier_price_after_tax'  => round($colaCost + $colaTax, 3),
                'supplier_discount_value'   => 0,
                'return_amount'             => 0,
            ]
        );

        // --- An UNAPPLIED inventory count (Water short 4, Cola over 5) --------
        $count = InventoryCount::firstOrCreate(
            ['store_id' => $store->id, 'description' => 'Monthly stock count — aisle 1'],
            ['physical_count_time' => Carbon::now()]   // change_stock_time null = not yet applied
        );

        // counted values chosen to show BOTH directions vs current stock
        foreach ([[$items[0], 336], [$items[2], 425]] as [$item, $counted]) {  // Water 340→336 (−4), Cola 420→425 (+5)
            InventoryCountItem::firstOrCreate(
                ['inventory_count_id' => $count->id, 'item_id' => $item->id],
                ['quantity_counted' => $counted]
            );
        }

        // --- An UNPROCESSED loss/damage (3x Croissant expired) ---------------
        $loss = LossAndDamage::firstOrCreate(
            ['store_id' => $store->id, 'description' => 'Expired stock — bakery'],
            ['comments' => '3x Croissant past sell-by date']   // exit_stock_time null = not written off
        );

        LossAndDamageItem::firstOrCreate(
            ['loss_and_damage_id' => $loss->id, 'item_id' => $items[11]->id],   // Croissant
            ['quantity' => 3]
        );
    }

    /**
     * Create a draft order with raw lines. All money columns start at 0
     * (the schema requires them NOT NULL) — OrderService->confirm recomputes them.
     */
    private function seedDraftOrder(Store $store, Customer $customer, int $adminId, string $orderNo, array $lookups, array $lines): void
    {
        $zeros = [
            'price_before_tax' => 0, 'total_tax_value' => 0, 'price_after_tax' => 0,
            'price_before_discount' => 0, 'order_items_discount' => 0, 'order_discount' => 0,
            'total_discount_value' => 0, 'price_after_discount' => 0, 'price' => 0,
        ];

        $order = OrderHeader::firstOrCreate(
            ['order_no' => $orderNo],
            array_merge($zeros, $lookups, [
                'store_id'      => $store->id,
                'customer_id'   => $customer->id,
                'created_by'    => $adminId,
                'latest_status' => 'Draft',
                'latest_status_update' => Carbon::now(),
                'is_submitted'  => false,
                'is_approved'   => false,
            ])
        );

        $lineZeros = [
            'current_item_cost' => 0, 'markup_percentage' => 0, 'price_before_tax' => 0,
            'tax_value' => 0, 'price_after_tax' => 0, 'price_before_discount' => 0,
            'discount_value' => 0, 'price_after_discount' => 0, 'price' => 0,
        ];

        foreach ($lines as $lineNo => [$item, $qty]) {
            OrderLine::firstOrCreate(
                ['order_id' => $order->id, 'item_id' => $item->id],
                array_merge($lineZeros, [
                    'store_id'    => $store->id,
                    'line_no'     => $lineNo + 1,
                    'quantity'    => $qty,
                    'is_canceled' => false,
                ])
            );
        }
    }
}
