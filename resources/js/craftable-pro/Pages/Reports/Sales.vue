<template>
    <PageHeader :title="$t('craftable-pro', 'Sales Report')" />

    <PageContent>
        <!-- Date filter -->
        <div class="mb-6 flex flex-wrap items-end gap-3">
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-500">From</label>
                <input type="date" v-model="from" class="rounded-lg border-gray-300 text-sm text-gray-900 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:[color-scheme:dark]" />
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-500">To</label>
                <input type="date" v-model="to" class="rounded-lg border-gray-300 text-sm text-gray-900 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:[color-scheme:dark]" />
            </div>
            <button @click="apply" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700">
                Apply
            </button>
            <a :href="exportUrl" class="ml-auto inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800">
                <ArrowDownTrayIcon class="h-4 w-4" />
                Export
            </a>
        </div>

        <!-- Summary -->
        <div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div v-for="s in cards" :key="s.label" class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">{{ s.label }}</p>
                <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">{{ s.value }}</p>
            </div>
        </div>

        <!-- Orders table -->
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800">
                <thead>
                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-gray-400">
                        <th class="px-5 py-3">Order</th>
                        <th class="px-5 py-3">Date</th>
                        <th class="px-5 py-3">Customer</th>
                        <th class="px-5 py-3">Paid</th>
                        <th class="px-5 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <tr v-for="o in orders" :key="o.order_no" class="text-sm">
                        <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">{{ o.order_no }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ o.date }}</td>
                        <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ o.customer }}</td>
                        <td class="px-5 py-3">
                            <span class="rounded-full px-2 py-0.5 text-xs font-medium" :class="o.is_paid ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-gray-100 text-gray-500 dark:bg-gray-800'">
                                {{ o.is_paid ? 'Paid' : 'Unpaid' }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right font-medium tabular-nums text-gray-900 dark:text-white">{{ money(o.total) }}</td>
                    </tr>
                    <tr v-if="!orders.length">
                        <td colspan="5" class="px-5 py-8 text-center text-sm text-gray-400">No sales in this period</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </PageContent>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { router } from "@inertiajs/vue3";
import { ArrowDownTrayIcon } from "@heroicons/vue/24/outline";
import { PageHeader, PageContent } from "craftable-pro/Components";

interface Props {
    filters: { from: string; to: string };
    summary: { total: number; count: number; average: number; paid: number };
    orders: Array<{ order_no: string; date: string; customer: string; total: number; is_paid: boolean }>;
}
const props = defineProps<Props>();

const from = ref(props.filters.from);
const to = ref(props.filters.to);

const money = (v: number) =>
    Number(v ?? 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + " DH";

const cards = computed(() => [
    { label: "Total sales", value: money(props.summary.total) },
    { label: "Orders", value: String(props.summary.count) },
    { label: "Avg order", value: money(props.summary.average) },
    { label: "Paid", value: money(props.summary.paid) },
]);

const apply = () => {
    router.get(route("craftable-pro.reports.sales"), { from: from.value, to: to.value }, { preserveState: true });
};

const exportUrl = computed(() =>
    route("craftable-pro.reports.sales.export", { from: from.value, to: to.value })
);
</script>
