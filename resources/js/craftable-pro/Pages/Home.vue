<template>
    <PageHeader :title="$t('craftable-pro', 'Dashboard')" />

    <PageContent>
        <!-- Stat cards (Larkon style) -->
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div v-for="s in stats" :key="s.label" class="rounded-xl border border-gray-200 bg-white p-5 dark:border-[#2c2f3d] dark:bg-[#212330]">
                <div class="flex items-center gap-4">
                    <span class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-xl bg-primary-500/10 text-primary-500">
                        <component :is="s.icon" class="h-7 w-7" />
                    </span>
                    <div class="ml-auto text-right">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">{{ s.label }}</p>
                        <p class="mt-0.5 text-2xl font-semibold" :class="s.danger ? 'text-red-500' : 'text-gray-900 dark:text-white'">{{ s.value }}</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-3 text-xs dark:border-[#2c2f3d]">
                    <span v-if="s.trend !== null" :class="s.trend >= 0 ? 'text-green-500' : 'text-red-500'" class="font-medium">
                        {{ s.trend >= 0 ? '▲' : '▼' }} {{ Math.abs(s.trend) }}% <span class="text-gray-400">this month</span>
                    </span>
                    <span v-else class="text-gray-400">{{ s.note }}</span>
                    <Link :href="s.href" class="font-medium text-primary-500 hover:text-primary-400">View More</Link>
                </div>
            </div>
        </div>

        <!-- Performance chart + Top selling -->
        <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-[#2c2f3d] dark:bg-[#212330] lg:col-span-2">
                <div class="mb-6 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Performance</h3>
                    <span class="text-xs text-gray-400">Sales · last 12 months</span>
                </div>
                <div class="flex h-56 items-end gap-2">
                    <div v-for="m in monthly" :key="m.label" class="flex flex-1 flex-col items-center gap-2">
                        <div class="flex w-full flex-1 items-end">
                            <div class="w-full rounded-t bg-primary-500/90 transition-all hover:bg-primary-500"
                                :style="{ height: barHeight(m.value) }" :title="money(m.value)"></div>
                        </div>
                        <span class="text-[0.65rem] text-gray-400">{{ m.label }}</span>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-[#2c2f3d] dark:bg-[#212330]">
                <h3 class="mb-4 text-sm font-semibold text-gray-900 dark:text-white">Top selling</h3>
                <div class="space-y-1">
                    <div v-for="(t, i) in topItems" :key="t.sku ?? i" class="flex items-center gap-3 rounded-lg px-1 py-2">
                        <span class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-md bg-primary-500/10 text-xs font-semibold text-primary-500">{{ i + 1 }}</span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-gray-900 dark:text-white">{{ t.name }}</p>
                            <p class="truncate text-xs text-gray-400">{{ t.sku }}</p>
                        </div>
                        <span class="text-sm font-semibold tabular-nums text-gray-700 dark:text-gray-300">{{ t.qty }}</span>
                    </div>
                    <p v-if="!topItems.length" class="py-6 text-center text-sm text-gray-400">No sales yet</p>
                </div>
            </div>
        </div>

        <!-- Recent orders -->
        <div class="rounded-xl border border-gray-200 bg-white dark:border-[#2c2f3d] dark:bg-[#212330]">
            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-[#2c2f3d]">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Recent orders</h3>
                <Link :href="route('craftable-pro.order-headers.index')" class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 dark:border-[#2c2f3d] dark:text-gray-300 dark:hover:bg-white/5">
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
    </PageContent>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { PageHeader, PageContent } from "craftable-pro/Components";
import { BanknotesIcon, ShoppingCartIcon, ExclamationTriangleIcon, DocumentTextIcon } from "@heroicons/vue/24/outline";

interface Props {
    stats: { sales_total: number; orders_count: number; low_stock_count: number; unpaid_invoices: number; sales_trend: number; orders_trend: number };
    monthly: Array<{ label: string; value: number }>;
    recentOrders: Array<{ order_no: string; customer: string; total: number; status: string; is_paid: boolean }>;
    topItems: Array<{ name: string; sku: string; qty: number }>;
}
const props = defineProps<Props>();

const money = (v: number) =>
    Number(v ?? 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + " DH";

const stats = computed(() => [
    { label: "Sales", value: money(props.stats.sales_total), icon: BanknotesIcon, trend: props.stats.sales_trend, href: route("craftable-pro.order-headers.index") },
    { label: "Orders", value: String(props.stats.orders_count), icon: ShoppingCartIcon, trend: props.stats.orders_trend, href: route("craftable-pro.order-headers.index") },
    { label: "Low stock", value: String(props.stats.low_stock_count), icon: ExclamationTriangleIcon, trend: null, note: "Needs attention", danger: props.stats.low_stock_count > 0, href: route("craftable-pro.items.index") },
    { label: "Unpaid invoices", value: String(props.stats.unpaid_invoices), icon: DocumentTextIcon, trend: null, note: "Awaiting payment", href: route("craftable-pro.invoices.index") },
]);

const maxMonthly = computed(() => Math.max(1, ...props.monthly.map((m) => m.value)));
const barHeight = (v: number) => `${Math.max(2, (v / maxMonthly.value) * 100)}%`;

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
