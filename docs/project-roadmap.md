# ecom-app — Project Roadmap

**Last updated:** 2026-06-22
**Goal:** Grow a Laravel e-commerce app into a professional, scalable product —
**admin back-office first, customer storefront second.**

---

## 1. Stack

| Layer | Choice |
|-------|--------|
| Framework | Laravel 12 (PHP 8.2+) |
| Admin panel | **Craftable PRO v3** (Brackets) at `/admin` |
| Frontend | Inertia.js v2 + Vue 3 + Tailwind 4 (Vite) |
| Database | MySQL (via Laravel Sail / Docker) |
| Queue/cache | Database driver today (Redis planned) |

> **Note:** `inertiajs/inertia-laravel` was pinned to `^2.0` (down from `^3.1`)
> because Craftable PRO v3 requires Inertia v2. Don't bump it back without
> confirming Craftable support.

---

## 2. Current state

- **Admin (Craftable PRO):** installed and serving at `http://localhost/admin`.
  CRUD generated for all 6 domain entities. Runs on its own auth guard
  (`craftable_pro_users`), separate from the app's `users` table.
- **Domain model:** `Product`, `Cart`, `CartItem`, `Order`, `OrderItem`,
  `Payment` — Eloquent **relations defined** across all of them
  (`Order → user / orderItems / payments`, `OrderItem → order / product`, etc.).
- **Money casts fixed:** models cast money columns as `decimal:2`
  (the generator emitted an invalid bare `decimal`, which crashed on read).
- **Customer storefront:** does **not exist yet** — `web.php` only has `/` and
  the Craftable admin routes. The default `web` guard (→ `users` table) is a
  clean slate for customer auth later.

---

## 3. Guiding decisions

1. **Admin first, storefront second.** Nail back-office data management before
   building the public shop.
2. **Craftable PRO = admin/staff only.** It is not a customer storefront;
   shoppers will never log into it.
3. **Non-destructive DB changes.** Past initial dev, schema changes go in new
   `*_alter_*` migrations, not edits to the originals (a `migrate:fresh` wipes
   the Craftable admin user + data).
4. **Plan before building.** Larger tracks get a short plan/section here and
   sign-off before code.

---

## 4. Admin roadmap (current focus)

Ordered by impact. None of these are started yet.

### Tier 1 — usability (highest impact)
- **A. Order management experience.** Order **detail page** (customer, line
  items, totals, payment + status), **status workflow** actions
  (pending → completed/cancelled), and relation-aware lists
  (show customer/product **names** + dropdowns instead of raw foreign-key IDs).
  Line-item *inline editing* is a deliberate phase 2.
- **B. Dashboard KPIs.** Replace the empty Craftable home with revenue, orders
  today, pending orders, low-stock, recent orders, a sales chart.

### Tier 2 — catalog & data quality
- **C. Product images** via Spatie MediaLibrary (already installed) instead of
  the `image_url` string.
- **D. Categories** as a real entity (`categories` table + `category_id`),
  replacing the free-text `products.category`.
- **E. Validation hardening** of the generated form requests
  (required, numeric/min, enum rules, unique slug auto-generated from name).

### Tier 3 — operations
- **F. Filters & smart columns** (order status/date, low-stock badges).
- **G. Roles & permissions** — define roles (Super Admin / Manager / Staff)
  over the per-resource permissions Craftable already generated.
- **H. Activity log** (`spatie/laravel-activitylog`) — audit who changed what.
- **I. Store settings** (currency, tax, store info) via Craftable settings.

---

## 5. Database / schema roadmap

**Tier 1 + most of Tier 2 APPLIED (2026-06-22)** via 4 non-destructive
`*_alter_*` migrations (`150010`–`150013`): order/history delete-protection
(nullOnDelete + product soft-deletes + order email snapshot), unique slug/sku,
`is_active`, unsigned stock, money standardized to `decimal(10,2)`, order
financial breakdown + `order_number` + currency + lifecycle timestamps + wider
status, cart-line uniqueness, payment refunds/`meta`/idempotency, and the
filter/sort indexes. Models updated to match (fillable + casts + SoftDeletes).

Still open: new tables (Tier 3) and the business-judgment items in §3
(guest carts; splitting payment vs fulfillment status).

### Tier 1 — correctness & integrity ✅ applied
- **Preserve order history:** `order_items.product_id` → nullable + `nullOnDelete`,
  and **soft-delete `products`** (currently a product delete cascades and wipes
  historical order lines). *Highest priority — real data-loss risk.*
- **Unique `products.slug`** (+ index) — storefront URLs key off it.
- **Standardize money precision** to `decimal(10,2)` across all money columns
  (`order_items.unit_price` is already 10,2; the rest are 8,2).
- **Indexes** on filtered/sorted columns: `orders.status`, `orders.created_at`,
  `products.category`, `payments.status`.
- **Unique `cart_items(cart_id, product_id)`** — one line per product.
  ⚠️ Rollback gotcha: MySQL ties the `cart_id` FK to this composite index;
  a reverting migration must drop/re-add the FK around it.

### Tier 2 — e-commerce essentials
- **`orders`:** human order number, `currency`, money breakdown
  (`subtotal`/`tax`/`shipping`/`discount`/`total`), `placed_at`/`paid_at`,
  wider status enum (processing/shipped/delivered/refunded).
- **`products`:** `sku` (unique), `is_active`/published flag.
- **`payments`:** `paid_at`, `meta` JSON (gateway payload).

### Tier 3 — growth tables
`addresses`, product variants, `coupons`/discounts, `stock_movements`
(inventory audit), `reviews`.

---

## 6. Storefront roadmap (later)

When admin is solid:
- **Customer auth** on the `web` guard (`users` table) — Fortify is already
  installed via Craftable; decide Fortify-headless vs a starter kit.
- **Inertia storefront app** (currently not bootstrapped) — public catalog,
  product detail, cart, checkout.
- **Payments integration** — Stripe (Laravel Cashier) / PayPal + webhooks;
  the `payments` table + provider enum already anticipate this.
- **Business logic** — cart → order conversion, stock decrement, totals/tax.

---

## 7. Engineering quality (cross-cutting, ongoing)

- **Tests** — Pest/PHPUnit feature tests (checkout & payments must be covered).
- **Static analysis** — Larastan/PHPStan; Pint already present for formatting.
- **CI/CD** — GitHub Actions: Pint + PHPStan + tests on every push.
- **Error tracking & backups** — Sentry/Flare, `spatie/laravel-backup`.
- **IDE** — optional `barryvdh/laravel-ide-helper` for facade/model hints.

---

## 8. Scale & ops (for "bigger")

Queues (already running `queue:listen` in dev) → Redis → Laravel Scout +
Meilisearch for catalog search → deployment (Forge/Envoyer or Vapor) with
staging + production environments.
