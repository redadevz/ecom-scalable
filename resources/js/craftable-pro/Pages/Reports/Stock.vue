<template>
    <PageHeader :title="$t('craftable-pro', 'Stock Report')" />

    <PageContent>
        <div class="mb-6 flex items-center">
            <a :href="exportUrl" class="ml-auto inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800">
                <ArrowDownTrayIcon class="h-4 w-4" />
                Export
            </a>
        </div>

        <!-- Summary -->
        <div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div v-for="s in cards" :key="s.label" class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">{{ s.label }}</p>
                <p class="mt-2 text-2xl font-semibold" :class="s.class ?? 'text-gray-900 dark:text-white'">{{ s.value }}</p>
            </div>
        </div>

        <!-- Items table -->
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800">
                <thead>
                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-gray-400">
                        <th class="px-5 py-3">Item</th>
                        <th class="px-5 py-3">Category</th>
                        <th class="px-5 py-3 text-right">Stock</th>
                        <th class="px-5 py-3 text-right">Unit Cost</th>
                        <th class="px-5 py-3 text-right">Value</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <tr v-for="it in items" :key="it.sku" class="text-sm">
                        <td class="px-5 py-3">
                            <div class="font-medium text-gray-900 dark:text-white">{{ it.name }}</div>
                            <div class="text-xs text-gray-400">{{ it.sku }}</div>
                        </td>
                        <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ it.category }}</td>
                        <td class="px-5 py-3 text-right">
                            <span class="rounded-full px-2 py-0.5 text-xs font-semibold tabular-nums"
                                :class="it.is_low ? 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300'">
                                {{ it.stock }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right tabular-nums text-gray-500">{{ money(it.cost) }}</td>
                        <td class="px-5 py-3 text-right font-medium tabular-nums text-gray-900 dark:text-white">{{ money(it.value) }}</td>
                    </tr>
                    <tr v-if="!items.length">
                        <td colspan="5" class="px-5 py-8 text-center text-sm text-gray-400">No items</td>
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

interface Props {
    summary: { items: number; low_stock: number; units: number; value: number };
    items: Array<{ name: string; sku: string; category: string; stock: number; low_stock: number; is_low: boolean; cost: number; value: number }>;
}
const props = defineProps<Props>();

const money = (v: number) =>
    Number(v ?? 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + " DH";

const cards = computed(() => [
    { label: "Items", value: String(props.summary.items) },
    { label: "Low stock", value: String(props.summary.low_stock), class: props.summary.low_stock ? "text-red-600" : "text-gray-900 dark:text-white" },
    { label: "Total units", value: String(props.summary.units) },
    { label: "Inventory value", value: money(props.summary.value) },
]);

const exportUrl = computed(() => route("craftable-pro.reports.stock.export"));
</script>
