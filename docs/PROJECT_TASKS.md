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
- ✅ **2. UI polish** — Larkon orange + dark theme, sectioned forms, Larkon-style tables (thumbnail + rounded action buttons), Items grid view, dashboard, login
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

### Step 17 — Dashboard KPIs (live data) ✅
- `DashboardController@index` overrides the home route (registered in `web.php` AFTER `Route::craftablePro`) → feeds `Pages/Home.vue`
- Live stats: sales total, orders count, low-stock count, unpaid invoices + recent orders table + top-selling items
- `Pages/Home.vue` rebuilt: KPI cards, recent orders, top selling, quick access
- Added **Dashboard** link to the sidebar (home icon, top)

> UI polish this phase: compact full sidebar (distinct icons per item), **Larkon-style orange + dark theme** across the whole project, two-column Item form (main + side panel) as the form template.

### Step 18 — Reports ✅
- ✅ **Sales report** — `ReportController@sales` + route `reports/sales` + `Pages/Reports/Sales.vue` + sidebar link
  - date-range filter (From/To), summary cards (total/orders/avg/paid), orders table
  - fixed: reactive summary via `computed`; white date text in dark mode; "Sales Report" added to translations
- ✅ **Stock report** — `ReportController@stock` (levels + low-stock flags + valuation), route `reports/stock`, `Pages/Reports/Stock.vue`
- ✅ **Purchases report** — `ReportController@purchases` (by supplier + date range), route `reports/purchases`, `Pages/Reports/Purchases.vue`
- ✅ **Excel export** — Maatwebsite Excel; `SalesReportExport` / `StockReportExport` / `PurchasesReportExport` in `app/Exports/` + `*Export` controller actions + download buttons

### Step 18b — Product images (Spatie Media Library) ✅
- Switched Item images from a simple `image` column to **Craftable's Spatie Media Library** (multiple images, thumbnails, managed in the Media module)
- `Item` implements `HasMedia` + Craftable media traits; `images` collection (≤5 MB), auto `preview` conversion; `images` / `images_url` accessors; `$appends`
- Form uses Craftable **`Dropzone`** (`form.images`); grid + list show `images_url` (fallback to old `image` column for seed data); `media` eager-loaded
- Verified end-to-end via tinker (upload → conversion → preview URL)

### Step 19 — Settings ✅
- **Shop Settings** via Spatie `laravel-settings` (already shipped w/ Craftable): `app/Settings/ShopSettings.php` + settings migration `2026_07_06_120000_create_shop_settings.php` (currency_code/symbol, default_tax_rate, negative_stock_allowed, low_stock_threshold)
- `ShopSettingsController@edit/update` + routes `settings/shop` (edit + put) + sidebar link (System group)
- `Pages/Settings/Shop.vue` — Larkon-style settings page (emoji section headers, currency preview card, Yes/No radios, sticky save bar)
- Wired `StockService::negativeStockAllowed()` → reads the real setting (flip toggle = sales can go below zero)
- Verified via tinker: settings resolve `MAD / DH / 20% / neg=false / low=5`
- Note: Craftable also ships its own **Settings** page (locales, default route, 2FA) — kept separate; Shop Settings is the business/POS layer

### Step 19b — Full front-end premium pass ✅
- **Larkon design system across the whole project** (all 55 modules)
- Global CSS refinement (`resources/css/craftable-pro.css`): premium cards, uppercase quiet table headers, hairline dividers, pill pagination, dark multiselects, rounded modals, badges
- **Every list table** curated to meaningful columns (no horizontal overflow): avatar/identity cell, status pills/toggles, rounded view/edit/delete action buttons — controllers gained eager-loads where needed (no N+1)
- **Every form** rebuilt as premium two-column: icon-headed sections + Flags (booleans as toggles) + Relations side cards; several with live-preview cards (Store/Supplier/Customer/Refund…)
- **Store logo** = real file upload (`StoreController@uploadLogo` + `stores/upload-logo` route + Dropzone in Store form)
- Reference templates: `Pages/Store/Index.vue` (table) + `Pages/Supplier/Form.vue` (form)
- ⚠️ Built + PHP-lint clean + 454 routes load, but not every screen browser-click-tested — spot-check create/edit saves

### Step 20 — Hardening 🔨  ← in progress
- ✅ **Automated service tests** (PHPUnit + `RefreshDatabase`, `tests/Feature/Services/`) — **19 tests green**:
  - `StockServiceTest` (5): in/out, history row, insufficient-stock guard, negative-stock setting, `isStockable`
  - `PurchaseServiceTest` (3): receive adds stock + stamps time, idempotent, skips services
  - `OrderServiceTest` (3): confirm (price→stock-out→approve→totals), cancel (stock back), double-cancel guard
  - `PaymentServiceTest` (5): settle marks paid, partial payments, already-paid guard, non-positive guard, refund flags return
- ✅ **`AdminSmokeTest`** — GETs every admin index+create route as admin (no 5xx across all 55 modules)
- ✅ **Fixed the factory suite** (was fatally broken project-wide): missing `Factory` import on all 55; alias/user-FK class-name bugs (`Timezone`→`TimeZone`, `Order`→`OrderHeader`, `CreatedBy`/etc.→null); unique-field collisions (`code`/`name`/`symbol` → `unique()`); `sequence_no` range; removed nonexistent `comments` from Invoice/SaleReturn factories
- ✅ **Schema-drift fix** — migration `2026_07_06_150000_add_comments_to_payments_and_refunds.php` (dev DB had `comments` on payments/refunds but the create-migrations didn't → fresh migrate broke `PaymentService`; guarded/idempotent)
- ✅ Deleted 55 dead auto-generated **API** CRUD tests (targeted a stripped REST API — 0 `api/` routes)
- ⬜ More service tests (Invoice, SaleReturn, StockReturn, InventoryCount, LossAndDamage)
- ⬜ Validation rules in every `Store*Request` / `Update*Request`
- ⬜ Authorization: permissions for confirm/receive/cancel/etc. + role assignment
- ⬜ Extend `stockIn` to record cost (`initial_item_cost`/`current_item_cost`)
- ⬜ Detail pages + empty states (tables already curated in Step 19b)

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
- Wire document module + attach files to orders/purchases (Spatie Media Library now in use — see Item images, Step 18b)

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
