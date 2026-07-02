# Project Roadmap — Retail POS / E-commerce (ecom-app)

The full project as an **ordered, step-by-step** checklist. Do the steps in number order.
Each step lists the **files** and the **goal** so you can build it yourself.

Legend: ✅ done · 🔨 in progress · ⬜ to do

> Tech: Laravel 12 · Craftable PRO (Inertia + Vue 3 + Tailwind) · MySQL · Sail
> Rebuild front-end after any `.vue` change: `./vendor/bin/sail npm run craftable-pro:build`
> Re-seed (idempotent): `./vendor/bin/sail artisan db:seed --class=RetailDataSeeder`

---

## ✅ Already done (steps 0–7)

- ✅ **0. Foundation** — Craftable PRO, ~81-table DB, 55 CRUD modules, models + relations
- ✅ **1. Seed data** — `database/seeders/RetailDataSeeder.php`
- ✅ **2. UI polish** — emerald theme, sectioned forms, curated tables, dashboard, login
- ✅ **3. StockService** — `stockIn`/`stockOut` + `InsufficientStockException`
- ✅ **4. PricingService** — line/order totals + `Price::scopeActive()`
- ✅ **5. DiscountService** — line/order discounts + `DiscountType::scopeActive()`
- ✅ **6. OrderService@confirm** — price → discount → totals → stock out → status
- ✅ **7. Confirm endpoint** — route + `OrderHeaderController@confirm` + button (tested live)

---

## 🔨 Do next — in this exact order

### Step 8 — PurchaseService (restock) ✅
- File: `app/Services/PurchaseService.php`
- Logic: `receive(Purchase)` → `stockIn` each line, stamp `entry_stock_time`, idempotent, in a transaction

### Step 9 — Wire the Receive endpoint ✅
- Route: `purchases/{purchase}/receive` (POST), name `purchases.receive` — `routes/web.php`
- Controller: `PurchaseController@receive(Purchase $purchase, PurchaseService $purchases)` ✅ (param `$purchase`)
- Button: "Receive Stock" in `Pages/Purchase/Edit.vue` (disables to "Received" once stocked) ✅
- Seed data: unreceived Purchase #1 in `RetailDataSeeder` for testing ✅

### Step 10 — OrderService@cancel (undo a sale) ✅
- File: `app/Services/OrderService.php` — `cancel()` + generalized `recordStatus($order, $statusName)`
- Logic: guard (not already canceled) → if approved, `stockIn` each non-service line → set
  `is_canceled`, `canceled_time`, `cancel_reason`, status `Cancelled` → record status row ✅
- Wire: route `order-headers/{orderHeader}/cancel` + `OrderHeaderController@cancel` + red Cancel button ✅
- Test: confirm then cancel → stock returns to pre-sale value

> ✅ Full stock loop now works end-to-end: **in (purchase receive) → out (sale confirm) → back (cancel)**

### Step 11 — SaleReturnService (customer returns) ✅
- File: `app/Services/SaleReturnService.php` (best-practice: row lock, domain exception, `restockLine()`, strict types)
- Exception: `app/Exceptions/SaleReturnAlreadyProcessedException.php` ✅
- Logic: `process(SaleReturn)` → `stockIn` returned qty, mark order line `return_quantity`/`return_time`/`return_required` ✅
- Wire: route `sale-returns/{saleReturn}/process` + `SaleReturnController@process` + "Process Return" button ✅
- Seed: unprocessed SaleReturn #1 (2× Fresh Milk on ORD-1002) for testing ✅
- (Refund money intentionally deferred to Step 16 — PaymentService)

### Step 12 — StockReturnService (return to supplier) ✅
- File: `app/Services/StockReturnService.php` (best-practice: row lock, domain exception, `processLine()`, strict types)
- Exception: `app/Exceptions/StockReturnAlreadyProcessedException.php` ✅
- Logic: `process(StockReturn)` → `stockOut` each line (goods leave), stamp `exit_stock_time`, idempotent ✅
- Wire: route `stock-returns/{stockReturn}/process` + `StockReturnController@process` (catches already-processed **and** insufficient-stock) + "Process Return" button ✅
- Seed: unprocessed StockReturn #1 (10× Cola Can) for testing ✅

> ✅ Stock engine now covers 5 operations: receive (in) · confirm (out) · cancel (back) · sale return (back) · stock return (out)

### Step 13 — InventoryCountService (stock correction) ✅
- File: `app/Services/InventoryCountService.php` (best-practice: row lock, domain exception, `applyLine()`, strict types)
- Exception: `app/Exceptions/InventoryCountAlreadyAppliedException.php` ✅
- Logic: `apply(InventoryCount)` → per item, diff counted vs current stock; `stockIn` (found extra) or `stockOut` (shrinkage); records `quantity_expected`/`quantity_change`; stamp `change_stock_time`, idempotent ✅
- Wire: route `inventory-counts/{inventoryCount}/apply` + `InventoryCountController@apply` (catches already-applied **and** insufficient-stock) ✅
- Refactor: added `StockService::isStockable(?Item)` — DRY guard used across services ✅
- Button "Apply Count" in `Pages/InventoryCount/Edit.vue` (disables to "Applied") ✅
- Seed: unapplied InventoryCount #1 (Water 340→336 = −4, Cola 420→425 = +5) — shows both directions ✅

### Step 14 — LossAndDamageService (write-off) ✅
- File: `app/Services/LossAndDamageService.php` (best-practice: row lock, domain exception, `writeOffLine()`, `isStockable()`)
- Exception: `app/Exceptions/LossAndDamageAlreadyProcessedException.php` ✅
- Logic: `apply(LossAndDamage)` → `stockOut` each lost/damaged line, stamp `exit_stock_time`, idempotent ✅
- Wire: route `loss-and-damages/{lossAndDamage}/apply` + `LossAndDamageController@apply` (catches already-processed **and** insufficient-stock) + red "Write Off" button ✅
- Seed: unprocessed LossAndDamage #1 (3× Croissant expired) for testing ✅

> ✅ **Stock domain complete** — 7 operations: receive (in) · confirm (out) · cancel (back) · sale return (back) · stock return (out) · inventory count (both) · loss/damage (out)

### Step 15 — InvoiceService ✅
- File: `app/Services/InvoiceService.php` (row lock, `OrderAlreadyInvoicedException`, guards approved + no double-invoice, `nextInvoiceNo()`)
- Exception: `app/Exceptions/OrderAlreadyInvoicedException.php` ✅
- Logic: `generate(OrderHeader)` → create `Invoice` + one `InvoiceLine` per non-canceled order line ✅
- Wire: route `order-headers/{orderHeader}/invoice` + `OrderHeaderController@invoice` + "Generate Invoice" button (enabled when approved & not canceled) ✅
- Demo data: `DemoOrdersSeeder` — 6 orders pushed through confirm → invoice → paid (real totals + stock) ✅

> ✅ Billing started — app now flows **stock → sale → invoice**. `DemoOrdersSeeder` populates Orders/Invoices/StockHistory with realistic data via the live services.

### Step 16 — PaymentService ✅
- File: `app/Services/PaymentService.php` (replaced a stale mislabeled `PricingService` draft)
- Logic: `record(Invoice, amount)` (partial payments, auto-marks `is_paid` when total ≥ order total),
  `settle(Invoice)` (pay full remaining balance), `refund(SaleReturn, amount)` (marks return refunded).
  `payment_method_id` defaults to first method (required column). ✅
- Wire: route `invoices/{invoice}/pay` + `InvoiceController@pay` + "Mark Paid" button (disabled when paid) ✅
- Tested: settled INV-000005 → Payment created + invoice paid ✅
- ✅ refund button on sale returns → `SaleReturnController@refund` (auto-computes amount = returned qty × order-line unit price) + `PaymentService::refund`

> ✅ **Core backend complete (steps 0–16)** — full retail cycle:
> purchase → stock in · sale → confirm (price/discount/stock out) → invoice → payment ·
> returns → stock back + refund · inventory count / loss → adjust.

### Step 17 — Dashboard KPIs (live data) ⬜  ← next
- Files: new `DashboardController` (or ext  end home) passing stats → `Pages/Home.vue`
- Logic: today's sales total, # orders, low-stock count, top items — replace quick-link cards with real numbers

### Step 18 — Reports ⬜
- Sales (by day/channel/customer), Stock (levels + low-stock + valuation), Purchases (by supplier)
- Date filters + CSV/Excel export (Maatwebsite Excel installed)

### Step 19 — Settings ⬜
- Store settings screen (currency, tax defaults, **negative-stock policy**)
- Wire `StockService::negativeStockAllowed()` to the real setting

### Step 20 — Hardening ⬜
- Validation rules in every `Store*Request` / `Update*Request`
- Authorization: permissions for confirm/receive/cancel/etc. + role assignment
- Extend `stockIn` to record cost (`initial_item_cost`/`current_item_cost`)
- Automated tests (Pest) per service
- Remaining table curation + empty states + detail pages

---

## After the admin is solid — Customer storefront

### Step 21 — Decide approach ⬜
- Same Laravel app (public routes + Inertia/Blade + Sanctum/Fortify) vs separate SPA — recommend same app

### Step 22 — Public catalog ⬜
- Product list, product detail, category browse + search + filters

### Step 23 — Customer accounts ⬜
- Register/login (Fortify installed; `customers.password` exists), profile, address book, order history

### Step 24 — Cart & checkout ⬜
- Cart → checkout creates `OrderHeader` + `OrderLine`s (reuse OrderService), online channel, payment gateway, confirmation email

### Step 25 — Post-purchase ⬜
- Order tracking, customer returns (→ SaleReturnService), reviews, loyalty points

---

## Platform & ops (alongside / near the end)

### Step 26 — Notifications ⬜
- Emails (order confirmed, invoice, low-stock to staff) + in-app notifications

### Step 27 — Jobs & automation ⬜
- Queues for emails/exports, scheduled daily sales summary + low-stock report, enforce holidays/discount schedules

### Step 28 — Documents ⬜
- Wire document module + attach files to orders/purchases (Spatie Media Library installed)

### Step 29 — Deployment ⬜
- Prod env, asset build, `config:cache`/`route:cache`, backups, HTTPS/domain, error monitoring

### Step 30 — QA & docs ⬜
- Full manual test pass, demo dataset, admin manual, keep `docs/database-schema.sql` + ER diagram current

---

## Conventions / gotchas (learned the hard way)

- **Route-model binding matches by NAME**: route `{orderHeader}` → controller param must be
  `$orderHeader` (not `$order`), else you get an empty model with a null id.
- **Money columns are `decimal(15,3)`** → round to 3 places in services.
- **All stock changes go through StockService** — never touch `items.current_stock_quantity` directly.
- **Wrap multi-step operations in `DB::transaction`** — all-or-nothing.
- **Services calculate / orchestrate; controllers stay thin** (validate → call service → redirect).
- **Skip service items** (`is_service`) in any stock operation.
- **Local Pages override vendor** (`resources/js/craftable-pro/Pages/...`).
- **Generated CRUD ships an API layer** you don't want — strip it after each generation.
- **Rebuild front-end** after `.vue` changes; **re-seed** is idempotent.
