<template>
    <PageHeader :title="$t('craftable-pro', 'Dashboard')" />

    <PageContent>
        <!-- Greeting -->
        <div class="mb-6">
            <p class="text-sm text-gray-500">{{ greeting }}</p>
            <h2 class="mt-0.5 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                Welcome back
            </h2>
        </div>

        <!-- KPI stat cards -->
        <div class="mb-8 grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div v-for="kpi in kpis" :key="kpi.label"
                class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">{{ kpi.label }}</p>
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg" :class="kpi.iconWrap">
                        <component :is="kpi.icon" class="h-4 w-4" />
                    </span>
                </div>
                <p class="mt-3 text-2xl font-semibold tracking-tight" :class="kpi.valueClass">{{ kpi.value }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Recent orders -->
            <div class="lg:col-span-2">
                <div class="mb-3 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Recent orders</h3>
                    <Link :href="route('craftable-pro.order-headers.index')" class="text-sm font-medium text-emerald-600 hover:text-emerald-500">
                        View all
                    </Link>
                </div>
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                    <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800">
                        <thead>
                            <tr class="text-left text-xs font-semibold uppercase tracking-wide text-gray-400">
                                <th class="px-5 py-3">Order</th>
                                <th class="px-5 py-3">Customer</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            <tr v-for="o in recentOrders" :key="o.order_no" class="text-sm">
                                <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">{{ o.order_no }}</td>
                                <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ o.customer }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" :class="statusClass(o.status)">
                                        {{ o.status || '—' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right font-medium tabular-nums text-gray-900 dark:text-white">{{ money(o.total) }}</td>
                            </tr>
                            <tr v-if="!recentOrders.length">
                                <td colspan="4" class="px-5 py-8 text-center text-sm text-gray-400">No orders yet</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top items -->
            <div>
                <h3 class="mb-3 text-sm font-semibold text-gray-900 dark:text-white">Top selling</h3>
                <div class="rounded-xl border border-gray-200 bg-white p-2 dark:border-gray-800 dark:bg-gray-900">
                    <div v-for="(t, i) in topItems" :key="t.sku ?? i"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5">
                        <span class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-md bg-gray-100 text-xs font-semibold text-gray-500 dark:bg-gray-800">{{ i + 1 }}</span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-gray-900 dark:text-white">{{ t.name }}</p>
                            <p class="truncate text-xs text-gray-400">{{ t.sku }}</p>
                        </div>
                        <span class="text-sm font-semibold tabular-nums text-gray-700 dark:text-gray-300">{{ t.qty }}</span>
                    </div>
                    <p v-if="!topItems.length" class="px-3 py-8 text-center text-sm text-gray-400">No sales yet</p>
                </div>
            </div>
        </div>

        <!-- Quick access -->
        <h3 class="mb-3 mt-8 text-xs font-semibold uppercase tracking-wide text-gray-400">Quick access</h3>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <Link v-for="card in cards" :key="card.label" :href="card.href"
                class="group flex items-start gap-4 rounded-xl border border-gray-200 bg-white p-5 transition-colors hover:border-gray-300 hover:bg-gray-50/50 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-800/50">
                <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-500 transition-colors group-hover:bg-emerald-50 group-hover:text-emerald-600 dark:bg-gray-800">
                    <component :is="card.icon" class="h-5 w-5" />
                </span>
                <div class="min-w-0">
                    <p class="font-medium text-gray-900 dark:text-white">{{ card.label }}</p>
                    <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">{{ card.description }}</p>
                </div>
            </Link>
        </div>
    </PageContent>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { PageHeader, PageContent } from "craftable-pro/Components";
import {
    CubeIcon, ShoppingCartIcon, UsersIcon, TagIcon, TruckIcon, ArchiveBoxIcon,
    BanknotesIcon, ExclamationTriangleIcon, DocumentTextIcon,
} from "@heroicons/vue/24/outline";

interface Props {
    stats: { sales_total: number; orders_count: number; low_stock_count: number; unpaid_invoices: number };
    recentOrders: Array<{ order_no: string; customer: string; total: number; status: string; is_paid: boolean }>;
    topItems: Array<{ name: string; sku: string; qty: number }>;
}
const props = defineProps<Props>();

const greeting = computed(() => {
    const h = new Date().getHours();
    if (h < 12) return "Good morning";
    if (h < 18) return "Good afternoon";
    return "Good evening";
});

const money = (v: number) =>
    Number(v ?? 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + " DH";

const kpis = computed(() => [
    { label: "Sales", value: money(props.stats.sales_total), icon: BanknotesIcon, iconWrap: "bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10", valueClass: "text-gray-900 dark:text-white" },
    { label: "Orders", value: String(props.stats.orders_count), icon: ShoppingCartIcon, iconWrap: "bg-sky-50 text-sky-600 dark:bg-sky-500/10", valueClass: "text-gray-900 dark:text-white" },
    { label: "Low stock", value: String(props.stats.low_stock_count), icon: ExclamationTriangleIcon, iconWrap: "bg-amber-50 text-amber-600 dark:bg-amber-500/10", valueClass: props.stats.low_stock_count ? "text-red-600" : "text-gray-900 dark:text-white" },
    { label: "Unpaid invoices", value: String(props.stats.unpaid_invoices), icon: DocumentTextIcon, iconWrap: "bg-violet-50 text-violet-600 dark:bg-violet-500/10", valueClass: "text-gray-900 dark:text-white" },
]);

const statusClass = (s: string) => {
    const map: Record<string, string> = {
        Draft: "bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300",
        Approved: "bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400",
        Completed: "bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400",
        Cancelled: "bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400",
    };
    return map[s] ?? "bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300";
};

const cards = [
    { label: "Items", description: "Manage your product catalog", icon: CubeIcon, href: route("craftable-pro.items.index") },
    { label: "Orders", description: "View and process sales orders", icon: ShoppingCartIcon, href: route("craftable-pro.order-headers.index") },
    { label: "Customers", description: "Customer records & loyalty", icon: UsersIcon, href: route("craftable-pro.customers.index") },
    { label: "Prices", description: "Pricing, tax & markups", icon: TagIcon, href: route("craftable-pro.prices.index") },
    { label: "Purchases", description: "Restock from suppliers", icon: TruckIcon, href: route("craftable-pro.purchases.index") },
    { label: "Stock history", description: "Audit every stock movement", icon: ArchiveBoxIcon, href: route("craftable-pro.stock-histories.index") },
];
</script>
