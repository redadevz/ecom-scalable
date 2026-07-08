<template>
    <PageHeader sticky title="Point of Sale">
        <div class="flex items-center gap-2">
            <span v-if="cartCount" class="hidden items-center gap-1.5 rounded-full bg-primary-500/10 px-3 py-1.5 text-sm font-semibold text-primary-600 dark:text-primary-400 sm:inline-flex">
                <ShoppingCartIcon class="h-4 w-4" /> {{ cartCount }}
            </span>
            <Button color="gray" variant="outline" :leftIcon="BanknotesIcon" :as="Link" :href="route('craftable-pro.pos.till')">
                Register
            </Button>
            <span class="mx-0.5 hidden h-6 w-px bg-gray-200 dark:bg-[#2c2f3d] sm:block" />
            <Button v-if="parked.length" color="gray" variant="outline" :leftIcon="InboxStackIcon" @click="showParked = true">
                Parked ({{ parked.length }})
            </Button>
            <Button color="gray" variant="outline" :leftIcon="PauseIcon" @click="holdSale" :disabled="!cart.length">
                Hold
            </Button>
            <Button color="gray" variant="outline" :leftIcon="TrashIcon" @click="resetCart" :disabled="!cart.length">
                Clear
            </Button>
        </div>
    </PageHeader>

    <PageContent>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            <!-- LEFT: search + results -->
            <section class="lg:col-span-7">
                <div class="relative">
                    <MagnifyingGlassIcon class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" />
                    <input ref="searchEl" v-model="query" type="text" @keydown.enter.prevent="onSearchEnter"
                        placeholder="Search or scan barcode / SKU…"
                        class="w-full rounded-xl border border-gray-200 bg-white py-3 pl-11 pr-4 text-sm text-gray-900 shadow-sm transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 dark:border-[#2c2f3d] dark:bg-[#1e2029] dark:text-white" />
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

                <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3 xl:grid-cols-4">
                    <button v-for="it in results" :key="it.id" type="button" @click="addToCart(it)"
                        class="group relative flex flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white text-left shadow-sm transition duration-150 hover:-translate-y-0.5 hover:border-primary-400 hover:shadow-md dark:border-[#2c2f3d] dark:bg-[#1e2029]">
                        <!-- image / initial -->
                        <div class="relative flex h-24 items-center justify-center overflow-hidden bg-gray-50 dark:bg-white/[0.03]">
                            <img v-if="it.image_url" :src="it.image_url" :alt="it.name" class="h-full w-full object-cover transition duration-200 group-hover:scale-105" />
                            <span v-else class="flex h-full w-full items-center justify-center bg-gradient-to-br from-primary-500/10 to-orange-500/5 text-2xl font-bold text-primary-500/50">{{ it.name.charAt(0).toUpperCase() }}</span>
                            <span class="absolute right-2 top-2 flex h-7 w-7 items-center justify-center rounded-full bg-primary-500 text-white opacity-0 shadow-lg transition duration-150 group-hover:opacity-100">
                                <PlusIcon class="h-4 w-4" />
                            </span>
                            <span v-if="!it.is_service && it.stock <= 0" class="absolute left-2 top-2 rounded-full bg-red-500 px-2 py-0.5 text-[10px] font-semibold text-white">out</span>
                        </div>
                        <!-- body -->
                        <div class="flex flex-1 flex-col p-3">
                            <span class="line-clamp-2 text-sm font-medium leading-snug text-gray-900 dark:text-white">{{ it.name }}</span>
                            <span class="mt-0.5 text-[11px] uppercase tracking-wide text-gray-400">{{ it.sku }}</span>
                            <div class="mt-auto flex items-center justify-between pt-2.5">
                                <span class="text-[15px] font-bold text-primary-600 dark:text-primary-400">{{ money(it.unit_price) }}</span>
                                <span v-if="!it.is_service" class="text-[11px] font-medium tabular-nums"
                                    :class="it.stock > 0 ? 'text-gray-400' : 'text-red-500'">{{ it.stock }} left</span>
                                <span v-else class="rounded-full bg-gray-100 px-1.5 py-0.5 text-[10px] font-medium text-gray-500 dark:bg-white/5">service</span>
                            </div>
                        </div>
                    </button>
                </div>

                <div v-if="!results.length && !searching" class="mt-12 flex flex-col items-center text-center text-gray-400">
                    <MagnifyingGlassIcon class="h-8 w-8 text-gray-300 dark:text-gray-600" />
                    <p class="mt-2 text-sm">No sellable items found.</p>
                </div>
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
                            <div class="mb-1 flex items-center justify-between">
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Payment method</label>
                                <button type="button" @click="toggleSplit" class="text-xs font-medium text-primary-600 hover:underline dark:text-primary-400">
                                    {{ splitMode ? 'Single payment' : 'Split payment' }}
                                </button>
                            </div>

                            <!-- single method -->
                            <div v-if="!splitMode" class="flex flex-wrap gap-2">
                                <button v-for="pm in paymentMethods" :key="pm.id" type="button" @click="paymentMethodId = pm.id"
                                    :class="paymentMethodId === pm.id ? 'border-primary-500 bg-primary-500/10 text-primary-600 dark:text-primary-400' : 'border-gray-200 text-gray-600 dark:border-[#2c2f3d] dark:text-gray-300'"
                                    class="rounded-xl border px-3 py-1.5 text-sm font-medium transition">{{ pm.name }}</button>
                            </div>

                            <!-- split payments -->
                            <div v-else class="space-y-2">
                                <div v-for="(pl, i) in paymentLines" :key="i" class="flex gap-2">
                                    <select v-model="pl.payment_method_id" class="w-1/2 rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm dark:border-[#2c2f3d] dark:bg-[#151720] dark:text-white">
                                        <option v-for="pm in paymentMethods" :key="pm.id" :value="pm.id">{{ pm.name }}</option>
                                    </select>
                                    <input v-model.number="pl.amount" type="number" min="0" step="0.01" placeholder="0.00"
                                        class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-right text-sm tabular-nums dark:border-[#2c2f3d] dark:bg-[#151720] dark:text-white" />
                                    <button type="button" @click="removePaymentLine(i)" class="text-gray-300 hover:text-red-500"><XMarkIcon class="h-4 w-4" /></button>
                                </div>
                                <div class="flex items-center justify-between pt-1 text-xs">
                                    <button type="button" @click="addPaymentLine" class="font-medium text-primary-600 hover:underline dark:text-primary-400">+ Add payment</button>
                                    <span :class="remaining > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-green-600 dark:text-green-400'">
                                        {{ remaining > 0 ? 'Remaining ' + money(remaining) : 'Fully covered' }}
                                    </span>
                                </div>
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
                        <div v-if="!splitMode">
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
                        <Button class="mt-4 w-full justify-center !py-3 text-base" :leftIcon="CheckIcon" :disabled="!cart.length || processing" :loading="processing" @click="checkout">
                            Charge {{ money(grandTotal) }}
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
                        <Button color="gray" variant="outline" class="flex-1 justify-center" :leftIcon="PrinterIcon" @click="printReceipt">Print</Button>
                        <Button class="flex-1 justify-center" @click="showReceipt = false">New sale</Button>
                    </div>
                </div>
            </div>
        </Transition>

        <!-- parked sales overlay -->
        <Transition enter-active-class="transition duration-150" enter-from-class="opacity-0" leave-active-class="transition duration-100" leave-to-class="opacity-0">
            <div v-if="showParked" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/50" @click="showParked = false"></div>
                <div class="relative w-full max-w-md rounded-2xl border border-gray-200 bg-white shadow-xl dark:border-[#2c2f3d] dark:bg-[#1e2029]">
                    <header class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-[#2a2d38]">
                        <h3 class="text-[15px] font-semibold text-gray-900 dark:text-white">Parked sales</h3>
                        <button type="button" @click="showParked = false" class="text-gray-400 hover:text-gray-600"><XMarkIcon class="h-5 w-5" /></button>
                    </header>
                    <div class="max-h-[60vh] overflow-y-auto p-3">
                        <p v-if="!parked.length" class="py-8 text-center text-sm text-gray-400">No parked sales.</p>
                        <div v-for="p in parked" :key="p.id" class="flex items-center gap-3 rounded-xl px-3 py-3 transition hover:bg-gray-50 dark:hover:bg-white/5">
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-gray-900 dark:text-white">{{ p.label }}</p>
                                <p class="text-xs text-gray-400">Held at {{ p.at }}</p>
                            </div>
                            <Button size="sm" @click="resumeSale(p)">Resume</Button>
                            <button type="button" @click="discardParked(p.id)" class="text-gray-300 hover:text-red-500"><TrashIcon class="h-4 w-4" /></button>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </PageContent>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted } from "vue";
import { router, Link } from "@inertiajs/vue3";
import { PageHeader, PageContent, Button } from "craftable-pro/Components";
import { MagnifyingGlassIcon, ShoppingCartIcon, PlusIcon, MinusIcon, XMarkIcon, PrinterIcon, PauseIcon, InboxStackIcon, TrashIcon, BanknotesIcon } from "@heroicons/vue/24/outline";
import { CheckIcon, CheckCircleIcon } from "@heroicons/vue/24/solid";

interface Result { id: number; name: string; sku: string; is_service: boolean; stock: number; unit_price: number; image_url: string | null; }
interface CartLine extends Result { quantity: number; }
interface Receipt { order_no: string; invoice_no: string; total: number; paid: boolean; items: number; }
interface Discount { type: "amount" | "percent"; value: number | null; }
interface Parked { id: number; label: string; at: string; lines: CartLine[]; customerId: number | null; discount: Discount; }
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
const splitMode = ref(false);
const paymentLines = ref<{ payment_method_id: number | null; amount: number | null }[]>([]);
const processing = ref(false);
const errors = ref<Record<string, string>>({});
const searchEl = ref<HTMLInputElement | null>(null);

const showReceipt = ref(!!props.receipt);
const receipt = ref<Receipt | null>(props.receipt);
const lastChange = ref<number | null>(null);
const lastSale = ref<SaleSnapshot | null>(null);

const parked = ref<Parked[]>([]);
const showParked = ref(false);
const PARK_KEY = "pos_parked";

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
const paidSum = computed(() => paymentLines.value.reduce((s, l) => s + (Number(l.amount) || 0), 0));
const remaining = computed(() => Math.max(0, +(grandTotal.value - paidSum.value).toFixed(2)));

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
    splitMode.value = false;
    paymentLines.value = [];
    errors.value = {};
}

// ---- Split / partial payment ----
function toggleSplit() {
    splitMode.value = !splitMode.value;
    if (splitMode.value && !paymentLines.value.length) {
        paymentLines.value = [{ payment_method_id: paymentMethodId.value, amount: +grandTotal.value.toFixed(2) }];
    }
}
function addPaymentLine() {
    paymentLines.value.push({ payment_method_id: props.payment_methods[0]?.id ?? null, amount: remaining.value || null });
}
function removePaymentLine(i: number) {
    paymentLines.value.splice(i, 1);
}

// ---- Hold / park a sale (persisted to localStorage) ----
function loadParked() {
    try { parked.value = JSON.parse(localStorage.getItem(PARK_KEY) || "[]"); } catch { parked.value = []; }
}
function persistParked() {
    localStorage.setItem(PARK_KEY, JSON.stringify(parked.value));
}
function holdSale() {
    if (!cart.value.length) return;
    const who = customerId.value ? " · " + (props.customers.find((c) => c.id === customerId.value)?.label ?? "") : "";
    parked.value.unshift({
        id: Date.now(),
        label: `${cartCount.value} item${cartCount.value === 1 ? "" : "s"} · ${money(grandTotal.value)}${who}`,
        at: new Date().toLocaleTimeString(),
        lines: JSON.parse(JSON.stringify(cart.value)),
        customerId: customerId.value,
        discount: JSON.parse(JSON.stringify(discount.value)),
    });
    persistParked();
    resetCart();
}
function resumeSale(p: Parked) {
    if (cart.value.length) holdSale();       // park the current cart first so nothing is lost
    cart.value = p.lines;
    customerId.value = p.customerId;
    discount.value = p.discount;
    parked.value = parked.value.filter((x) => x.id !== p.id);
    persistParked();
    showParked.value = false;
}
function discardParked(id: number) {
    parked.value = parked.value.filter((x) => x.id !== id);
    persistParked();
}

function checkout() {
    if (!cart.value.length) return;
    processing.value = true;
    errors.value = {};

    const change = !splitMode.value && tendered.value !== null && tendered.value >= grandTotal.value ? tendered.value - grandTotal.value : null;
    lastChange.value = change;

    const splitPayments = splitMode.value ? paymentLines.value.filter((l) => Number(l.amount) > 0) : [];
    const paymentLabel = splitMode.value
        ? splitPayments.map((l) => props.payment_methods.find((pm) => pm.id === l.payment_method_id)?.name).filter(Boolean).join(" + ")
        : (props.payment_methods.find((pm) => pm.id === paymentMethodId.value)?.name ?? "");

    // snapshot for the printable receipt (cart is cleared on success)
    lastSale.value = {
        lines: cart.value.map((l) => ({ name: l.name, sku: l.sku, qty: l.quantity, unit: l.unit_price, total: l.unit_price * l.quantity })),
        subtotal: subtotal.value,
        discount: discountAmount.value,
        grandTotal: grandTotal.value,
        customer: customerId.value ? (props.customers.find((c) => c.id === customerId.value)?.label ?? "") : "Walk-in",
        payment: paymentLabel,
        tendered: tendered.value,
        change,
    };

    router.post(route("craftable-pro.pos.checkout"), {
        customer_id: customerId.value,
        payment_method_id: paymentMethodId.value,
        pay_now: !splitMode.value,
        discount: discountAmount.value > 0 ? { type: discount.value.type, value: discount.value.value } : null,
        payments: splitMode.value ? splitPayments : null,
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
    loadParked();
    fetchItems();          // show products by default
    searchEl.value?.focus();
});
</script>
