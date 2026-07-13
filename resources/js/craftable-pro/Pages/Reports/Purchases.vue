<template>
    <PageHeader :title="$t('craftable-pro', 'Purchases Report')" />

    <PageContent>
        <div class="mb-6 flex items-center">
            <a :href="exportUrl" class="ml-auto inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800">
                <ArrowDownTrayIcon class="h-4 w-4" />
                Export
            </a>
        </div>

        <!-- Summary -->
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div v-for="s in cards" :key="s.label" class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">{{ s.label }}</p>
                <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">{{ s.value }}</p>
            </div>
        </div>

        <!-- Charts -->
        <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900 lg:col-span-2">
                <h3 class="mb-4 text-sm font-semibold text-gray-900 dark:text-white">Spend by supplier</h3>
                <ReportChart type="bars" :data="bySupplier" format="money" />
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <h3 class="mb-4 text-sm font-semibold text-gray-900 dark:text-white">Received vs pending</h3>
                <ReportChart type="donut" :data="receivedSplit" :center-label="String(summary.purchases)" center-sub="orders" />
            </div>
        </div>

        <!-- By supplier -->
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800">
                <thead>
                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-gray-400">
                        <th class="px-5 py-3">Supplier</th>
                        <th class="px-5 py-3 text-right">Purchases</th>
                        <th class="px-5 py-3 text-right">Received</th>
                        <th class="px-5 py-3 text-right">Total Spend</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <tr v-for="r in rows" :key="r.supplier" class="text-sm">
                        <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">{{ r.supplier }}</td>
                        <td class="px-5 py-3 text-right tabular-nums text-gray-600 dark:text-gray-300">{{ r.purchases }}</td>
                        <td class="px-5 py-3 text-right tabular-nums text-gray-500">{{ r.received }} / {{ r.purchases }}</td>
                        <td class="px-5 py-3 text-right font-medium tabular-nums text-gray-900 dark:text-white">{{ money(r.spend) }}</td>
                    </tr>
                    <tr v-if="!rows.length">
                        <td colspan="4" class="px-5 py-8 text-center text-sm text-gray-400">No purchases</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </PageContent>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { ArrowDownTrayIcon } from "@heroicons/vue/24/outline";
import { PageHeader, PageContent } from "craftable-pro/Components";
import ReportChart from "@/craftable-pro/Components/ReportChart.vue";

interface Props {
    summary: { suppliers: number; purchases: number; spend: number };
    rows: Array<{ supplier: string; purchases: number; received: number; spend: number }>;
}
const props = defineProps<Props>();

const money = (v: number) =>
    Number(v ?? 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + " DH";

const cards = computed(() => [
    { label: "Suppliers", value: String(props.summary.suppliers) },
    { label: "Purchases", value: String(props.summary.purchases) },
    { label: "Total spend", value: money(props.summary.spend) },
]);

// top suppliers by spend (rows already sorted desc by spend server-side)
const bySupplier = computed(() =>
    props.rows.slice(0, 8).map((r) => ({ label: r.supplier, value: r.spend }))
);

const receivedSplit = computed(() => {
    const received = props.rows.reduce((a, r) => a + r.received, 0);
    return [
        { label: "Received", value: received, color: "#10b981" },
        { label: "Pending", value: Math.max(0, props.summary.purchases - received), color: "#f59e0b" },
    ];
});

const exportUrl = computed(() => route("craftable-pro.reports.purchases.export"));
</script>
