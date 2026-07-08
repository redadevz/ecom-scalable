<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Point of Sale')">
        <span v-if="cartCount" class="mr-2 hidden items-center rounded-full bg-primary-500/10 px-3 py-1 text-sm font-medium text-primary-600 dark:text-primary-400 sm:inline-flex">
            {{ cartCount }} {{ cartCount === 1 ? 'item' : 'items' }}
        </span>
        <Button color="gray" variant="outline" @click="resetCart" :disabled="!cart.length">
            {{ $t("craftable-pro", "Clear") }}
        </Button>
    </PageHeader>

    <PageContent>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            <!-- LEFT: search + results -->
            <section class="lg:col-span-7">
                <div class="relative">
                    <MagnifyingGlassIcon class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" />
                    <input ref="searchEl" v-model="query" type="text" @keydown.enter.prevent="onSearchEnter"
                        :placeholder="$t('craftable-pro', 'Search or scan barcode / SKU…')"
                        class="w-full rounded-xl border border-gray-200 bg-white py-3 pl-11 pr-4 text-sm text-gray-900 shadow-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:border-[#2c2f3d] dark:bg-[#1e2029] dark:text-white" />
                </div>

                <!-- category filter -->
                <div v-if="categories.length" class="mt-4 flex flex-wrap gap-2">
                    <button type="button" @click="categoryId = null"
                        :class="categoryId === null ? 'border-primary-500 bg-primary-500/10 text-primary-600 dark:text-primary-400' : 'border-gray-200 text-gray-600 hover:border-gray-300 dark:border-[#2c2f3d] dark:text-gray-300'"
                        class="rounded-full border px-3 py-1.5 text-sm font-medium transition">All</button>
                    <button v-for="cat in categories" :key="cat.id" type="button" @click="categoryId = cat.id"
                        :class="categoryId === cat.id ? 'border-primary-500 bg-primary-500/10 text-primary-600 dark:text-primary-400' : 'border-gray-200 text-gray-600 hover:border-gray-300 dark:border-[#2c2f3d] dark:text-gray-300'"
                        class="rounded-full border px-3 py-1.5 text-sm font-medium transition">{{ cat.name }}</button>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3">
                    <button v-for="it in results" :key="it.id" type="button" @click="addToCart(it)"
                        class="group flex flex-col rounded-xl border border-gray-200 bg-white p-3 text-left shadow-sm transition hover:border-primary-400 hover:shadow dark:border-[#2c2f3d] dark:bg-[#1e2029]">
                        <div class="mb-2 flex h-24 items-center justify-center overflow-hidden rounded-lg bg-gray-50 dark:bg-white/5">
                            <img v-if="it.image_url" :src="it.image_url" :alt="it.name" class="h-full w-full object-cover" />
                            <CubeIcon v-else class="h-8 w-8 text-gray-300 dark:text-gray-600" />
                        </div>
                        <div class="flex items-start justify-between gap-2">
                            <span class="line-clamp-2 text-sm font-medium text-gray-900 dark:text-white">{{ it.name }}</span>
                            <PlusCircleIcon class="h-5 w-5 flex-shrink-0 text-gray-300 transition group-hover:text-primary-500" />
                        </div>
                        <span class="mt-0.5 text-xs text-gray-400">{{ it.sku }}</span>
                        <div class="mt-2 flex items-center justify-between">
                            <span class="text-sm font-semibold text-primary-600 dark:text-primary-400">{{ money(it.unit_price) }}</span>
                            <span v-if="!it.is_service" class="rounded-full px-1.5 py-0.5 text-[11px] font-medium"
                                :class="it.stock > 0 ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400'">
                                {{ it.stock }} in stock
                            </span>
                            <span v-else class="rounded-full bg-gray-100 px-1.5 py-0.5 text-[11px] font-medium text-gray-500 dark:bg-white/5">service</span>
                        </div>
                    </button>
                </div>

                <p v-if="!results.length && !searching" class="mt-8 text-center text-sm text-gray-400">
                    {{ $t("craftable-pro", "No sellable items found.") }}
                </p>
            </section>

            <!-- RIGHT: cart -->
            <section class="lg:col-span-5">
                <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029] lg:sticky lg:top-24">
                    <header class="flex items-center gap-3 border-b border-gray-100 px-5 py-4 dark:border-[#2a2d38]">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-500/10 text-primary-500"><ShoppingCartIcon class="h-5 w-5" /></span>
                        <h3 class="text-[15px] font-semibold text-gray-900 dark:text-white">Current sale</h3>
                    </header>

                    <!-- lines -->
                    <div class="max-h-[34vh] overflow-y-auto px-5 py-3">
                        <div v-if="!cart.length" class="py-10 text-center text-sm text-gray-400">Cart is empty.</div>
                        <div v-for="line in cart" :key="line.id" class="flex items-center gap-3 py-2.5">
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-gray-900 dark:text-white">{{ line.name }}</p>
                                <p class="text-xs text-gray-400">{{ money(line.unit_price) }} each</p>
                            </div>
                            <div class="flex items-center gap-1">
                                <button type="button" @click="dec(line)" class="flex h-7 w-7 items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 dark:border-[#2c2f3d] dark:text-gray-300 dark:hover:bg-white/5"><MinusIcon class="h-4 w-4" /></button>
                                <input v-model.number="line.quantity" type="number" min="1" @change="normalize(line)" class="w-12 rounded-lg border border-gray-200 bg-white py-1 text-center text-sm dark:border-[#2c2f3d] dark:bg-[#151720] dark:text-white" />
                                <button type="button" @click="inc(line)" class="flex h-7 w-7 items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 dark:border-[#2c2f3d] dark:text-gray-300 dark:hover:bg-white/5"><PlusIcon class="h-4 w-4" /></button>
                            </div>
                            <span class="w-20 text-right text-sm font-semibold tabular-nums text-gray-900 dark:text-white">{{ money(line.unit_price * line.quantity) }}</span>
                            <button type="button" @click="remove(line)" class="text-gray-300 hover:text-red-500"><XMarkIcon class="h-4 w-4" /></button>
                        </div>
                    </div>

                    <!-- customer + payment + discount -->
                    <div class="space-y-3 border-t border-gray-100 px-5 py-4 dark:border-[#2a2d38]">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Customer (optional)</label>
                            <select v-model="customerId" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm dark:border-[#2c2f3d] dark:bg-[#151720] dark:text-white">
                                <option :value="null">Walk-in</option>
                                <option v-for="c in customers" :key="c.id" :value="c.id">{{ c.label }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Payment method</label>
                            <div class="flex flex-wrap gap-2">
                                <button v-for="pm in paymentMethods" :key="pm.id" type="button" @click="paymentMethodId = pm.id"
                                    :class="paymentMethodId === pm.id ? 'border-primary-500 bg-primary-500/10 text-primary-600 dark:text-primary-400' : 'border-gray-200 text-gray-600 dark:border-[#2c2f3d] dark:text-gray-300'"
                                    class="rounded-xl border px-3 py-1.5 text-sm font-medium transition">{{ pm.name }}</button>
                            </div>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Discount (optional)</label>
                            <div class="flex gap-2">
                                <div class="inline-flex overflow-hidden rounded-xl border border-gray-200 dark:border-[#2c2f3d]">
                                    <button type="button" @click="discount.type = 'amount'"
                                        :class="discount.type === 'amount' ? 'bg-primary-500 text-white' : 'text-gray-500 dark:text-gray-400'"
                                        class="px-3 py-2 text-sm font-medium transition">{{ currency }}</button>
                                    <button type="button" @click="discount.type = 'percent'"
                                        :class="discount.type === 'percent' ? 'bg-primary-500 text-white' : 'text-gray-500 dark:text-gray-400'"
                                        class="px-3 py-2 text-sm font-medium transition">%</button>
                                </div>
                                <input v-model.number="discount.value" type="number" min="0" step="0.01" placeholder="0"
                                    class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-right text-sm tabular-nums dark:border-[#2c2f3d] dark:bg-[#151720] dark:text-white" />
                            </div>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Cash tendered (optional)</label>
                            <input v-model.number="tendered" type="number" min="0" step="0.01" :placeholder="grandTotal.toFixed(2)"
                                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-right text-sm tabular-nums dark:border-[#2c2f3d] dark:bg-[#151720] dark:text-white" />
                            <p v-if="tendered !== null && tendered >= grandTotal" class="mt-1 text-right text-xs text-green-600 dark:text-green-400">Change: {{ money(tendered - grandTotal) }}</p>
                        </div>
                    </div>

                    <!-- totals + pay -->
                    <div class="border-t border-gray-100 px-5 py-4 dark:border-[#2a2d38]">
                        <div v-if="discountAmount > 0" class="mb-2 space-y-1 text-sm">
                            <div class="flex justify-between text-gray-500 dark:text-gray-400"><span>Subtotal</span><span class="tabular-nums">{{ money(subtotal) }}</span></div>
                            <div class="flex justify-between text-primary-600 dark:text-primary-400"><span>Discount</span><span class="tabular-nums">- {{ money(discountAmount) }}</span></div>
                        </div>
                        <div class="flex items-baseline justify-between">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Total <span class="text-xs font-normal text-gray-400">(incl. tax)</span></span>
                            <span class="text-2xl font-bold tabular-nums text-primary-600 dark:text-primary-400">{{ money(grandTotal) }}</span>
                        </div>
                        <p v-if="errors.lines" class="mt-2 text-xs text-red-500">{{ errors.lines }}</p>
                        <Button class="mt-4 w-full justify-center" :leftIcon="CheckIcon" :disabled="!cart.length || processing" :loading="processing" @click="checkout">
                            {{ $t("craftable-pro", "Charge") }} {{ money(grandTotal) }}
                        </Button>
                    </div>
                </div>
            </section>
        </div>

        <!-- receipt overlay -->
        <Transition enter-active-class="transition duration-150" enter-from-class="opacity-0" leave-active-class="transition duration-100" leave-to-class="opacity-0">
            <div v-if="showReceipt" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/50" @click="showReceipt = false"></div>
                <div class="relative w-full max-w-sm rounded-2xl border border-gray-200 bg-white p-6 text-center shadow-xl dark:border-[#2c2f3d] dark:bg-[#1e2029]">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-green-100 text-green-600 dark:bg-green-500/10 dark:text-green-400"><CheckCircleIcon class="h-8 w-8" /></div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">Sale complete</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Order <b>{{ receipt?.order_no }}</b> · Invoice <b>{{ receipt?.invoice_no }}</b></p>
                    <div class="mt-5 space-y-1.5 rounded-xl bg-gray-50 p-4 text-left text-sm dark:bg-white/5">
                        <div class="flex justify-between text-gray-500 dark:text-gray-400"><span>Items</span><span>{{ receipt?.items }}</span></div>
                        <div class="flex justify-between text-gray-500 dark:text-gray-400"><span>Status</span><span :class="receipt?.paid ? 'text-green-600 dark:text-green-400' : 'text-amber-600 dark:text-amber-400'">{{ receipt?.paid ? 'Paid' : 'Unpaid' }}</span></div>
                        <div class="flex justify-between border-t border-gray-200 pt-2 font-semibold text-gray-900 dark:border-white/10 dark:text-white"><span>Total</span><span class="tabular-nums">{{ money(receipt?.total || 0) }}</span></div>
                        <div v-if="lastChange !== null" class="flex justify-between text-gray-500 dark:text-gray-400"><span>Change given</span><span class="tabular-nums">{{ money(lastChange) }}</span></div>
                    </div>
                    <div class="mt-5 flex gap-3">
                        <Button color="gray" variant="outline" class="flex-1 justify-center" :leftIcon="PrinterIcon" @click="printReceipt">{{ $t("craftable-pro", "Print") }}</Button>
                        <Button class="flex-1 justify-center" @click="showReceipt = false">{{ $t("craftable-pro", "New sale") }}</Button>
                    </div>
                </div>
            </div>
        </Transition>
    </PageContent>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted } from "vue";
import { router } from "@inertiajs/vue3";
import { PageHeader, PageContent, Button } from "craftable-pro/Components";
import { MagnifyingGlassIcon, ShoppingCartIcon, PlusIcon, MinusIcon, XMarkIcon, CubeIcon, PrinterIcon } from "@heroicons/vue/24/outline";
import { PlusCircleIcon, CheckIcon, CheckCircleIcon } from "@heroicons/vue/24/solid";

interface Result { id: number; name: string; sku: string; is_service: boolean; stock: number; unit_price: number; image_url: string | null; }
interface CartLine extends Result { quantity: number; }
interface Receipt { order_no: string; invoice_no: string; total: number; paid: boolean; items: number; }
interface Discount { type: "amount" | "percent"; value: number | null; }
interface SaleSnapshot {
    lines: { name: string; sku: string; qty: number; unit: number; total: number }[];
    subtotal: number; discount: number; grandTotal: number;
    customer: string; payment: string; tendered: number | null; change: number | null;
}

interface Props {
    customers: { id: number; label: string }[];
    categories: { id: number; name: string }[];
    payment_methods: { id: number; name: string }[];
    currency: string;
    receipt: Receipt | null;
}
const props = defineProps<Props>();

const query = ref("");
const categoryId = ref<number | null>(null);
const results = ref<Result[]>([]);
const searching = ref(false);
const cart = ref<CartLine[]>([]);
const customerId = ref<number | null>(null);
const paymentMethodId = ref<number | null>(props.payment_methods[0]?.id ?? null);
const discount = ref<Discount>({ type: "amount", value: null });
const tendered = ref<number | null>(null);
const processing = ref(false);
const errors = ref<Record<string, string>>({});
const searchEl = ref<HTMLInputElement | null>(null);

const showReceipt = ref(!!props.receipt);
const receipt = ref<Receipt | null>(props.receipt);
const lastChange = ref<number | null>(null);
const lastSale = ref<SaleSnapshot | null>(null);

const money = (v: number) => Number(v ?? 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + " " + props.currency;
const subtotal = computed(() => cart.value.reduce((s, l) => s + l.unit_price * l.quantity, 0));
const discountAmount = computed(() => {
    const v = Number(discount.value.value ?? 0);
    if (v <= 0) return 0;
    const raw = discount.value.type === "percent" ? subtotal.value * (v / 100) : v;
    return Math.min(raw, subtotal.value);
});
const grandTotal = computed(() => Math.max(0, subtotal.value - discountAmount.value));
const cartCount = computed(() => cart.value.reduce((s, l) => s + l.quantity, 0));

async function fetchItems() {
    searching.value = true;
    try {
        const params = new URLSearchParams();
        if (query.value.trim()) params.set("q", query.value.trim());
        if (categoryId.value !== null) params.set("category_id", String(categoryId.value));
        const res = await fetch(route("craftable-pro.pos.search") + "?" + params.toString(), { headers: { Accept: "application/json" } });
        results.value = await res.json();
    } finally {
        searching.value = false;
    }
}

// debounced on typing
let timer: ReturnType<typeof setTimeout>;
watch(query, () => {
    clearTimeout(timer);
    timer = setTimeout(fetchItems, 250);
});

// immediate on category change
watch(categoryId, fetchItems);

// Enter = scan: fetch now, and if it resolves to a single item, drop it in the cart.
async function onSearchEnter() {
    clearTimeout(timer);
    await fetchItems();
    if (results.value.length === 1) {
        addToCart(results.value[0]);
        query.value = "";      // clear + reload full grid for the next scan
    }
}

function addToCart(it: Result) {
    const found = cart.value.find((l) => l.id === it.id);
    if (found) found.quantity++;
    else cart.value.push({ ...it, quantity: 1 });
}
const inc = (l: CartLine) => l.quantity++;
const dec = (l: CartLine) => { l.quantity > 1 ? l.quantity-- : remove(l); };
const normalize = (l: CartLine) => { if (!l.quantity || l.quantity < 1) l.quantity = 1; };
const remove = (l: CartLine) => { cart.value = cart.value.filter((x) => x.id !== l.id); };
function resetCart() {
    cart.value = [];
    tendered.value = null;
    customerId.value = null;
    discount.value = { type: "amount", value: null };
    errors.value = {};
}

function checkout() {
    if (!cart.value.length) return;
    processing.value = true;
    errors.value = {};

    const change = tendered.value !== null && tendered.value >= grandTotal.value ? tendered.value - grandTotal.value : null;
    lastChange.value = change;

    // snapshot for the printable receipt (cart is cleared on success)
    lastSale.value = {
        lines: cart.value.map((l) => ({ name: l.name, sku: l.sku, qty: l.quantity, unit: l.unit_price, total: l.unit_price * l.quantity })),
        subtotal: subtotal.value,
        discount: discountAmount.value,
        grandTotal: grandTotal.value,
        customer: customerId.value ? (props.customers.find((c) => c.id === customerId.value)?.label ?? "") : "Walk-in",
        payment: props.payment_methods.find((pm) => pm.id === paymentMethodId.value)?.name ?? "",
        tendered: tendered.value,
        change,
    };

    router.post(route("craftable-pro.pos.checkout"), {
        customer_id: customerId.value,
        payment_method_id: paymentMethodId.value,
        pay_now: true,
        discount: discountAmount.value > 0 ? { type: discount.value.type, value: discount.value.value } : null,
        lines: cart.value.map((l) => ({ item_id: l.id, quantity: l.quantity })),
    }, {
        preserveScroll: true,
        onSuccess: () => { resetCart(); fetchItems(); },
        onError: (e) => { errors.value = e as Record<string, string>; },
        onFinish: () => { processing.value = false; },
    });
}

function printReceipt() {
    const r = receipt.value;
    const s = lastSale.value;
    if (!r) return;

    const cur = props.currency;
    const fmt = (v: number) => Number(v ?? 0).toFixed(2) + " " + cur;
    const lineRows = (s?.lines ?? []).map((l) =>
        `<tr><td>${escapeHtml(l.name)}<br><span class="muted">${l.qty} × ${fmt(l.unit)}</span></td><td class="r">${fmt(l.total)}</td></tr>`
    ).join("");

    const html = `<!doctype html><html><head><meta charset="utf-8"><title>${r.order_no}</title>
<style>
  * { font-family: 'Courier New', monospace; }
  body { width: 280px; margin: 0 auto; padding: 12px; color: #000; }
  h1 { font-size: 15px; text-align: center; margin: 0 0 2px; }
  .center { text-align: center; }
  .muted { color: #555; font-size: 11px; }
  hr { border: none; border-top: 1px dashed #999; margin: 8px 0; }
  table { width: 100%; border-collapse: collapse; font-size: 12px; }
  td { padding: 3px 0; vertical-align: top; }
  .r { text-align: right; white-space: nowrap; }
  .tot td { font-weight: bold; font-size: 14px; padding-top: 6px; }
  .row { display: flex; justify-content: space-between; font-size: 12px; }
</style></head><body onload="window.print()">
  <h1>RECEIPT</h1>
  <div class="center muted">${r.order_no} · ${r.invoice_no}</div>
  <hr>
  <table>${lineRows}</table>
  <hr>
  ${s && s.discount > 0 ? `<div class="row"><span>Subtotal</span><span>${fmt(s.subtotal)}</span></div><div class="row"><span>Discount</span><span>- ${fmt(s.discount)}</span></div>` : ""}
  <table><tr class="tot"><td>TOTAL</td><td class="r">${fmt(r.total)}</td></tr></table>
  ${s ? `<div class="row"><span>Payment</span><span>${escapeHtml(s.payment)}</span></div>` : ""}
  ${s && s.tendered !== null ? `<div class="row"><span>Tendered</span><span>${fmt(s.tendered)}</span></div>` : ""}
  ${s && s.change !== null ? `<div class="row"><span>Change</span><span>${fmt(s.change)}</span></div>` : ""}
  <hr>
  <div class="center muted">${escapeHtml(s?.customer ?? "")}</div>
  <div class="center muted">Thank you!</div>
</body></html>`;

    const w = window.open("", "_blank", "width=340,height=640");
    if (!w) return;
    w.document.write(html);
    w.document.close();
}

function escapeHtml(str: string): string {
    return String(str ?? "").replace(/[&<>"']/g, (c) => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c] as string));
}

onMounted(() => {
    fetchItems();          // show products by default
    searchEl.value?.focus();
});
</script>
