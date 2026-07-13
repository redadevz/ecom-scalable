<template>
    <PageHeader :title="$t('craftable-pro', 'Dashboard')" />

    <PageContent>
        <!-- Stat cards -->
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div v-for="(s, i) in stats" :key="s.label"
                class="stat-card group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 dark:border-[#2c2f3d] dark:bg-[#212330]"
                :style="{ animationDelay: `${i * 80}ms` }">
                <!-- glow -->
                <div class="pointer-events-none absolute -right-8 -top-8 h-28 w-28 rounded-full opacity-0 blur-2xl transition-opacity duration-500 group-hover:opacity-100"
                    :class="s.glow"></div>

                <div class="relative flex items-center gap-4">
                    <span class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br shadow-lg transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-3"
                        :class="[s.grad, s.shadow]">
                        <component :is="s.icon" class="h-7 w-7 text-white" />
                    </span>
                    <div class="ml-auto text-right">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">{{ s.label }}</p>
                        <p class="mt-0.5 text-2xl font-bold tabular-nums" :class="s.danger ? 'text-red-500' : 'text-gray-900 dark:text-white'">{{ s.display }}</p>
                    </div>
                </div>
                <div class="relative mt-4 flex items-center justify-between border-t border-gray-100 pt-3 text-xs dark:border-[#2c2f3d]">
                    <span v-if="s.trend !== null" class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 font-semibold"
                        :class="s.trend >= 0 ? 'bg-green-50 text-green-600 dark:bg-green-500/10 dark:text-green-400' : 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400'">
                        <component :is="s.trend >= 0 ? ArrowTrendingUpIcon : ArrowTrendingDownIcon" class="h-3.5 w-3.5" />
                        {{ Math.abs(s.trend) }}%
                    </span>
                    <span v-else class="text-gray-400">{{ s.note }}</span>
                    <Link :href="s.href" class="font-medium text-primary-500 transition-colors hover:text-primary-400">View More →</Link>
                </div>
            </div>
        </div>

        <!-- Performance chart + Top selling donut -->
        <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Area chart -->
            <div class="stat-card rounded-2xl border border-gray-200 bg-white p-5 dark:border-[#2c2f3d] dark:bg-[#212330] lg:col-span-2" style="animation-delay:120ms">
                <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Performance</h3>
                        <p class="text-xs text-gray-400">Last 12 months</p>
                    </div>
                    <div class="inline-flex rounded-lg bg-gray-100 p-0.5 dark:bg-[#2c2f3d]">
                        <button v-for="m in metrics" :key="m.key" @click="metric = m.key"
                            class="rounded-md px-3 py-1 text-xs font-semibold transition-all"
                            :class="metric === m.key ? 'bg-white text-primary-600 shadow-sm dark:bg-[#212330] dark:text-primary-400' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'">
                            {{ m.label }}
                        </button>
                    </div>
                </div>

                <div class="relative">
                    <svg :viewBox="`0 0 ${W} ${H}`" class="w-full" :style="{ height: '224px' }" preserveAspectRatio="none"
                        @mousemove="onMove" @mouseleave="active = -1">
                        <defs>
                            <linearGradient :id="gradId" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="rgb(var(--p))" stop-opacity="0.35" />
                                <stop offset="100%" stop-color="rgb(var(--p))" stop-opacity="0" />
                            </linearGradient>
                        </defs>
                        <!-- grid -->
                        <line v-for="g in 4" :key="g" :x1="0" :x2="W" :y1="(H - pad) * g / 4" :y2="(H - pad) * g / 4"
                            stroke="currentColor" class="text-gray-100 dark:text-[#2c2f3d]" stroke-width="1" />
                        <!-- area -->
                        <path :d="areaPath" :fill="`url(#${gradId})`" :style="{ opacity: anim }" />
                        <!-- line -->
                        <path ref="lineEl" :d="linePath" fill="none" stroke="rgb(var(--p))" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round"
                            :style="{ strokeDasharray: lineLen, strokeDashoffset: lineLen * (1 - anim) }" />
                        <!-- hover marker -->
                        <g v-if="active >= 0" :style="{ opacity: anim }">
                            <line :x1="pts[active].x" :x2="pts[active].x" :y1="0" :y2="H - pad" stroke="rgb(var(--p))" stroke-width="1" stroke-dasharray="3 3" opacity="0.4" />
                            <circle :cx="pts[active].x" :cy="pts[active].y" r="5" fill="rgb(var(--p))" stroke="white" stroke-width="2" class="dark:stroke-[#212330]" />
                        </g>
                    </svg>

                    <!-- tooltip -->
                    <div v-if="active >= 0" class="pointer-events-none absolute z-10 -translate-x-1/2 -translate-y-full rounded-lg bg-gray-900 px-2.5 py-1.5 text-xs text-white shadow-lg dark:bg-black"
                        :style="{ left: `${(pts[active].x / W) * 100}%`, top: `${(pts[active].y / H) * 224}px` }">
                        <div class="font-semibold">{{ series[active].label }}</div>
                        <div class="text-primary-300">{{ metric === 'sales' ? money(series[active].value) : series[active].orders + ' orders' }}</div>
                    </div>

                    <!-- x labels -->
                    <div class="mt-2 flex justify-between px-0.5">
                        <span v-for="m in series" :key="m.label" class="text-[0.65rem] text-gray-400">{{ m.label }}</span>
                    </div>
                </div>
            </div>

            <!-- Top selling donut -->
            <div class="stat-card rounded-2xl border border-gray-200 bg-white p-5 dark:border-[#2c2f3d] dark:bg-[#212330]" style="animation-delay:200ms">
                <h3 class="mb-4 text-sm font-semibold text-gray-900 dark:text-white">Top selling</h3>

                <div v-if="topItems.length" class="flex flex-col items-center">
                    <div class="relative h-40 w-40">
                        <svg viewBox="0 0 42 42" class="h-full w-full -rotate-90">
                            <circle cx="21" cy="21" r="15.915" fill="none" stroke="currentColor" class="text-gray-100 dark:text-[#2c2f3d]" stroke-width="4" />
                            <circle v-for="(seg, i) in donut" :key="i" cx="21" cy="21" r="15.915" fill="none"
                                :stroke="seg.color" stroke-width="4" stroke-linecap="round"
                                :stroke-dasharray="`${seg.len * anim} ${100 - seg.len * anim}`"
                                :stroke-dashoffset="seg.offset" class="transition-[stroke-dasharray] duration-300" />
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ Math.round(totalQty * anim) }}</span>
                            <span class="text-[0.65rem] uppercase tracking-wide text-gray-400">units</span>
                        </div>
                    </div>
                    <div class="mt-5 w-full space-y-2">
                        <div v-for="(t, i) in topItems" :key="t.sku ?? i" class="flex items-center gap-2.5 text-sm">
                            <span class="h-2.5 w-2.5 flex-shrink-0 rounded-full" :style="{ background: palette[i % palette.length] }"></span>
                            <span class="min-w-0 flex-1 truncate text-gray-700 dark:text-gray-300">{{ t.name }}</span>
                            <span class="font-semibold tabular-nums text-gray-900 dark:text-white">{{ t.qty }}</span>
                        </div>
                    </div>
                </div>
                <p v-else class="py-16 text-center text-sm text-gray-400">No sales yet</p>
            </div>
        </div>

        <!-- Recent orders -->
        <div class="stat-card overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-[#2c2f3d] dark:bg-[#212330]" style="animation-delay:260ms">
            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-[#2c2f3d]">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Recent orders</h3>
                <Link :href="route('craftable-pro.order-headers.index')" class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 transition-colors hover:bg-gray-50 dark:border-[#2c2f3d] dark:text-gray-300 dark:hover:bg-white/5">
                    View All
                </Link>
            </div>
            <table class="min-w-full divide-y divide-gray-100 dark:divide-[#2c2f3d]">
                <thead>
                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-gray-400">
                        <th class="px-5 py-3">Order</th>
                        <th class="px-5 py-3">Customer</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-[#2c2f3d]">
                    <tr v-for="(o, i) in recentOrders" :key="o.order_no"
                        class="row-in text-sm transition-colors hover:bg-gray-50 dark:hover:bg-white/5" :style="{ animationDelay: `${300 + i * 50}ms` }">
                        <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">{{ o.order_no }}</td>
                        <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ o.customer }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" :class="statusClass(o.status)">
                                {{ o.status || '—' }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right font-semibold tabular-nums text-gray-900 dark:text-white">{{ money(o.total) }}</td>
                    </tr>
                    <tr v-if="!recentOrders.length">
                        <td colspan="4" class="px-5 py-8 text-center text-sm text-gray-400">No orders yet</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </PageContent>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from "vue";
import { Link } from "@inertiajs/vue3";
import { PageHeader, PageContent } from "craftable-pro/Components";
import {
    BanknotesIcon, ShoppingCartIcon, ExclamationTriangleIcon, DocumentTextIcon,
    ArrowTrendingUpIcon, ArrowTrendingDownIcon,
} from "@heroicons/vue/24/outline";

interface Props {
    stats: { sales_total: number; orders_count: number; low_stock_count: number; unpaid_invoices: number; sales_trend: number; orders_trend: number };
    monthly: Array<{ label: string; value: number; orders: number }>;
    recentOrders: Array<{ order_no: string; customer: string; total: number; status: string; is_paid: boolean }>;
    topItems: Array<{ name: string; sku: string; qty: number }>;
}
const props = defineProps<Props>();

const money = (v: number) =>
    Number(v ?? 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + " DH";

/* ── one rAF drives every animation (count-up, line draw, donut sweep) ── */
const anim = ref(0);
onMounted(() => {
    const D = 1100, t0 = performance.now();
    const tick = (t: number) => {
        const p = Math.min(1, (t - t0) / D);
        anim.value = 1 - Math.pow(1 - p, 3); // easeOutCubic
        if (p < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
});

/* ── stat cards ── */
const stats = computed(() => [
    { label: "Sales", target: props.stats.sales_total, fmt: money, icon: BanknotesIcon, trend: props.stats.sales_trend,
      grad: "from-emerald-400 to-teal-500", shadow: "shadow-emerald-500/30", glow: "bg-emerald-400", href: route("craftable-pro.order-headers.index") },
    { label: "Orders", target: props.stats.orders_count, fmt: (v: number) => String(Math.round(v)), icon: ShoppingCartIcon, trend: props.stats.orders_trend,
      grad: "from-sky-400 to-indigo-500", shadow: "shadow-sky-500/30", glow: "bg-sky-400", href: route("craftable-pro.order-headers.index") },
    { label: "Low stock", target: props.stats.low_stock_count, fmt: (v: number) => String(Math.round(v)), icon: ExclamationTriangleIcon, trend: null, note: "Needs attention",
      danger: props.stats.low_stock_count > 0, grad: "from-amber-400 to-orange-500", shadow: "shadow-amber-500/30", glow: "bg-amber-400", href: route("craftable-pro.items.index") },
    { label: "Unpaid invoices", target: props.stats.unpaid_invoices, fmt: (v: number) => String(Math.round(v)), icon: DocumentTextIcon, trend: null, note: "Awaiting payment",
      grad: "from-rose-400 to-pink-500", shadow: "shadow-rose-500/30", glow: "bg-rose-400", href: route("craftable-pro.invoices.index") },
].map((s) => ({ ...s, display: s.fmt(s.target * anim.value) })));

/* ── performance area chart ── */
const metric = ref<"sales" | "orders">("sales");
const metrics = [{ key: "sales", label: "Sales" }, { key: "orders", label: "Orders" }] as const;
const series = computed(() => props.monthly);

const W = 600, H = 240, pad = 24;
const values = computed(() => series.value.map((m) => (metric.value === "sales" ? m.value : m.orders)));
const maxV = computed(() => Math.max(1, ...values.value));
const pts = computed(() =>
    values.value.map((v, i) => ({
        x: (i / Math.max(1, values.value.length - 1)) * W,
        y: (H - pad) - (v / maxV.value) * (H - pad - 10) + 5,
    }))
);
// smooth cubic path through points
const linePath = computed(() => {
    const p = pts.value;
    if (!p.length) return "";
    let d = `M ${p[0].x} ${p[0].y}`;
    for (let i = 0; i < p.length - 1; i++) {
        const c = (p[i].x + p[i + 1].x) / 2;
        d += ` C ${c} ${p[i].y}, ${c} ${p[i + 1].y}, ${p[i + 1].x} ${p[i + 1].y}`;
    }
    return d;
});
const areaPath = computed(() => (pts.value.length ? `${linePath.value} L ${W} ${H - pad} L 0 ${H - pad} Z` : ""));
const gradId = "perfGrad";

const lineEl = ref<SVGPathElement | null>(null);
const lineLen = ref(1);
onMounted(() => { if (lineEl.value) lineLen.value = lineEl.value.getTotalLength(); });

const active = ref(-1);
function onMove(e: MouseEvent) {
    const r = (e.currentTarget as SVGElement).getBoundingClientRect();
    const rel = (e.clientX - r.left) / r.width;
    active.value = Math.max(0, Math.min(pts.value.length - 1, Math.round(rel * (pts.value.length - 1))));
}

/* ── top-selling donut ── */
const palette = ["#f97316", "#6366f1", "#10b981", "#f43f5e", "#eab308"];
const totalQty = computed(() => props.topItems.reduce((a, t) => a + t.qty, 0));
const donut = computed(() => {
    let offset = 25; // start at top (rotated -90 already; 25 aligns nicely)
    return props.topItems.map((t, i) => {
        const len = totalQty.value ? (t.qty / totalQty.value) * 100 : 0;
        const seg = { len, offset, color: palette[i % palette.length] };
        offset = (offset - len + 100) % 100;
        return seg;
    });
});

const statusClass = (s: string) => {
    const map: Record<string, string> = {
        Draft: "bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300",
        Approved: "bg-primary-50 text-primary-700 dark:bg-primary-500/10 dark:text-primary-400",
        Completed: "bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400",
        Cancelled: "bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400",
    };
    return map[s] ?? "bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300";
};
</script>

<style scoped>
/* primary (Larkon orange #f97316) → rgb channels for SVG gradients/strokes */
.stat-card { --p: 249 115 22; }

.stat-card {
    opacity: 0;
    animation: cardIn 0.55s cubic-bezier(0.22, 1, 0.36, 1) forwards;
}
.row-in {
    opacity: 0;
    animation: rowIn 0.4s ease-out forwards;
}
@keyframes cardIn {
    from { opacity: 0; transform: translateY(16px) scale(0.98); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
@keyframes rowIn {
    from { opacity: 0; transform: translateX(-8px); }
    to   { opacity: 1; transform: translateX(0); }
}
@media (prefers-reduced-motion: reduce) {
    .stat-card, .row-in { animation: none; opacity: 1; }
}
</style>
