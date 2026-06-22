# Admin: Order Management + Relations — Implementation Plan

**Status:** Proposed (awaiting approval) · **Date:** 2026-06-22
**Track:** Admin Tier 1 — highest impact
**Goal:** Turn the Craftable-generated Orders admin from raw foreign-key rows into a
real order-management experience, and surface related data (customer, products,
payments) as names/labels instead of IDs.

---

## Context (current state)

The generated Orders resource is bare scaffolding:

- **List** (`Order/Index.vue`) shows `id`, `user_id`, `status`, `total`, `created_at`
  as raw text — `user_id` is a bare number, `status` is plain text, `total` is unformatted.
- **No detail (show) page** — only Index / Create / Edit / Form exist.
- **Form** (`Order/Form.vue`) uses plain text inputs for `user_id`, `status`, `total`.
- Controller (`OrderController`) uses Spatie QueryBuilder + Inertia, selects scalar
  columns only, eager-loads nothing.
- Routes live in `routes/web.php` inside repeated
  `Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')` groups.

Relations are already defined on the models (`Order belongsTo User`, `hasMany OrderItem`,
`hasMany Payment`; `OrderItem belongsTo Product`).

---

## Scope of this pass

### 1. Relation-aware order list
**Files:** `app/Http/Controllers/CraftablePro/OrderController.php`,
`resources/js/craftable-pro/Pages/Order/Index.vue`,
`resources/js/craftable-pro/Pages/Order/types.d.ts`

- Controller `index()`:
  - `->with(['user:id,name', 'payments:id,order_id,status'])`
  - `->withCount('orderItems')`
  - keep `user_id` in the select (needed for the relation).
- List columns:
  - `User Id` → **Customer name** (`item.user?.name`), fallback to `#id` if missing.
  - `Status` → **colored badge** (pending = amber, completed = green, cancelled = gray/red).
  - `Total` → **currency formatted**.
  - new **Items** column = `order_items_count`.
  - new **Payment** column = latest payment status as a badge (or "—").
- `types.d.ts`: extend `Order` with `user`, `payments`, `order_items_count`.

### 2. Order detail (show) page — NEW
**Files:** `OrderController@show`, new route in `routes/web.php`,
`resources/js/craftable-pro/Pages/Order/Show.vue`

- New route: `GET admin/orders/{order}` → name `craftable-pro.orders.show`,
  authorized via the existing `craftable-pro.orders.edit` permission
  (reuse `EditOrderRequest`).
- `show()` returns `Inertia::render('Order/Show', ...)` with the order plus
  `user`, `orderItems.product`, `payments`.
- Page layout:
  - Header: `Order #{id}` + status badge + action buttons (see §3).
  - **Customer** card (name, email).
  - **Line items** table: product name · quantity · unit price · line subtotal.
  - **Total** row.
  - **Payments** list: provider · reference · amount · currency · status badge.
- Add a **view (eye) icon button** to each row in `Index.vue` actions, linking to show.

### 3. Status workflow — NEW
**Files:** `OrderController@updateStatus`, new route in `routes/web.php`

- New route: `PATCH admin/orders/{order}/status` → name
  `craftable-pro.orders.update-status` (authorized via `orders.edit`).
- `updateStatus()` validates `status ∈ {pending, completed, cancelled}`,
  updates, redirects back with the standard Craftable success message.
- Detail page buttons: **Mark completed** / **Mark cancelled** (hide the button
  matching the current status).

### 4. Relation-aware create/edit form
**Files:** `OrderController@create` + `@edit`, `Order/Form.vue`, `Order/types.d.ts`

- Controller `create()` / `edit()` pass:
  - `users` → `User::select('id','name')->orderBy('name')->get()`
  - `statuses` → `['pending','completed','cancelled']`
- `Form.vue`:
  - `user_id` text input → **searchable `Multiselect`** (customer picker).
  - `status` text input → **`Multiselect`/select** with the 3 enum options.
  - `total` left as a numeric input for now (normally derived from items — see follow-up).

### 5. Build & verify
- `./vendor/bin/sail npm run craftable-pro:build`
- `./vendor/bin/sail artisan route:list | grep orders` → confirm `show` + `update-status`.
- Smoke-test: list renders names/badges, detail page shows items + payments,
  status buttons work, create/edit dropdowns work.

---

## Line items — phasing decision

Editing an order's line items **inline** (nested add/remove repeater with
auto-recalculated total + stock handling) is a meaningfully larger feature.

- **Phase 1 (this plan):** line items are **read-only** on the detail page.
  They remain editable through the existing **Order Items** CRUD.
- **Phase 2 (follow-up, separate plan):** nested line-item editor inside the
  order form, recalculating `total`, with optional stock decrement.

**Recommendation:** ship Phase 1 first.

---

## Out of scope (tracked for later)

- Inline line-item editing (Phase 2 above).
- Dashboard KPIs, product images (MediaLibrary), categories entity,
  validation hardening, roles/permissions — separate admin tracks.
- Applying the same relation-aware treatment to Cart / CartItem / Payment /
  OrderItem lists (can follow the same pattern once Orders is approved).

---

## Risk / notes

- All changes are to **generated** Craftable files; re-running
  `craftable-pro:generate-crud orders` would overwrite them. Document this so we
  don't regenerate over the work.
- New UI strings use Craftable's `$t('craftable-pro', …)` / `___('craftable-pro', …)`
  helpers; missing translations fall back to the key text (no breakage).
- No DB/migration changes. No new packages.
