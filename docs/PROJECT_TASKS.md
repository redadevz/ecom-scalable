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

### Step 8 — PurchaseService (restock) 🔨
- File: `app/Services/PurchaseService.php`
- Logic: `receive(Purchase)` → `stockIn` each line, stamp `entry_stock_time`, idempotent, in a transaction

### Step 9 — Wire the Receive endpoint ⬜
- Route: `purchases/{purchase}/receive` (POST), name `purchases.receive` — `routes/web.php`
- Controller: `PurchaseController@receive(Purchase $purchase, PurchaseService $purchases)`
  - ⚠️ param **`$purchase`** must match `{purchase}`
- Button: "Receive Stock" in `Pages/Purchase/Edit.vue` (mirror the Confirm button)
- Test: receive a purchase → stock goes up, `entry_stock_time` set, can't receive twice

### Step 10 — OrderService@cancel (undo a sale) ⬜
- File: `app/Services/OrderService.php`
- Logic: guard (approved & not canceled) → `stockIn` each non-service line → set
  `is_canceled`, `canceled_time`, `cancel_reason`, status `Cancelled` → record status row
- Wire: route `order-headers/{orderHeader}/cancel` + `OrderHeaderController@cancel` + button
- Test: confirm then cancel → stock returns to pre-sale value

### Step 11 — SaleReturnService (customer returns) ⬜
- File: `app/Services/SaleReturnService.php`
- Logic: `process(SaleReturn)` → `stockIn` returned qty, set order line `return_quantity`/`return_time`,
  optionally create a Refund row
- Wire endpoint + button · Test

### Step 12 — StockReturnService (return to supplier) ⬜
- File: `app/Services/StockReturnService.php`
- Logic: `process(StockReturn)` → `stockOut` each line (goods leave)
- Wire endpoint + button · Test

### Step 13 — InventoryCountService (stock correction) ⬜
- File: `app/Services/InventoryCountService.php`
- Logic: `apply(InventoryCount)` → per item, diff counted vs current; `stockIn`/`stockOut` the difference
- Wire endpoint + button · Test

### Step 14 — LossAndDamageService (write-off) ⬜
- File: `app/Services/LossAndDamageService.php`
- Logic: `apply(LossAndDamage)` → `stockOut` each lost/damaged line
- Wire endpoint + button · Test

### Step 15 — InvoiceService ⬜
- File: `app/Services/InvoiceService.php`
- Logic: `generate(OrderHeader)` → create `Invoice` + `InvoiceLine` per line, no double-invoice
- Wire (auto on confirm, or its own button) · Test

### Step 16 — PaymentService ⬜
- File: `app/Services/PaymentService.php`
- Logic: `record(Invoice, ...)` → create `Payment`, mark `is_paid` when settled; refunds for returns
- Wire endpoint + button · Test

### Step 17 — Dashboard KPIs (live data) ⬜
- Files: new `DashboardController` (or extend home) passing stats → `Pages/Home.vue`
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
