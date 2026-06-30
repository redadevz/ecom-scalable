<template>
    <PageHeader :title="$t('craftable-pro', 'Sale Returns')">
        <Button
            :leftIcon="PlusIcon"
            :as="Link"
            :href="route('craftable-pro.sale-returns.create')"
            v-can="'craftable-pro.sale-returns.create'"
        >
            {{ $t("craftable-pro", "New Sale Return") }}
        </Button>
    </PageHeader>

    <PageContent>
        <Listing
            :baseUrl="route('craftable-pro.sale-returns.index')"
            :data="saleReturns"
            dataKey="saleReturns"
        >
            <template #bulkActions="{ bulkAction }">
                <Modal type="danger">
                    <template #trigger="{ setIsOpen }">
                        <Button @click="() => setIsOpen(true)" color="gray" variant="outline" size="sm" :leftIcon="TrashIcon" v-can="'craftable-pro.sale-returns.destroy'">
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                    </template>
                    <template #title>{{ $t("craftable-pro", "Delete Sale Return") }}</template>
                    <template #content>{{ $t("craftable-pro", "Are you sure? This action cannot be undone.") }}</template>
                    <template #buttons="{ setIsOpen }">
                        <Button @click.prevent="() => { bulkAction('post', route('craftable-pro.sale-returns.bulk-destroy'), { onFinish: () => setIsOpen(false) }); }" color="danger" v-can="'craftable-pro.sale-returns.destroy'">
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                        <Button @click.prevent="() => setIsOpen()" color="gray" variant="outline">{{ $t("craftable-pro", "Cancel") }}</Button>
                    </template>
                </Modal>
            </template>

            <template #tableHead>
                <ListingHeaderCell sortBy='id'>{{ $t("craftable-pro", "Reference") }}</ListingHeaderCell>
                <ListingHeaderCell>{{ $t("craftable-pro", "Order") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='description'>{{ $t("craftable-pro", "Description") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='entry_stock_time'>{{ $t("craftable-pro", "Status") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='is_refunded'>{{ $t("craftable-pro", "Refund") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='created_at'>{{ $t("craftable-pro", "Created") }}</ListingHeaderCell>
                <ListingHeaderCell><span class="sr-only">{{ $t("craftable-pro", "Actions") }}</span></ListingHeaderCell>
            </template>

            <template #tableRow="{ item, action }: any">
                <ListingDataCell>
                    <span class="font-medium text-gray-900 dark:text-white">#{{ item.id }}</span>
                </ListingDataCell>
                <ListingDataCell>
                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ item.order?.order_no ?? '—' }}</span>
                </ListingDataCell>
                <ListingDataCell>
                    <span class="text-sm text-gray-500">{{ item.description || '—' }}</span>
                </ListingDataCell>
                <ListingDataCell>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" :class="item.entry_stock_time ? doneClass : pendingClass">
                        {{ item.entry_stock_time ? $t("craftable-pro", "Processed") : $t("craftable-pro", "Pending") }}
                    </span>
                </ListingDataCell>
                <ListingDataCell>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" :class="refundClass(item)">
                        {{ refundLabel(item) }}
                    </span>
                </ListingDataCell>
                <ListingDataCell>
                    <span class="text-sm text-gray-500">{{ item.created_at && dayjs(item.created_at).format('DD MMM YYYY') }}</span>
                </ListingDataCell>
                <ListingDataCell>
                    <div class="flex items-center justify-end gap-3">
                        <IconButton :as="Link" :href="route('craftable-pro.sale-returns.edit', item)" variant="ghost" color="gray" :icon="PencilSquareIcon" v-can="'craftable-pro.sale-returns.edit'" />
                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <IconButton @click="() => setIsOpen(true)" color="gray" variant="ghost" :icon="TrashIcon" v-can="'craftable-pro.sale-returns.destroy'" />
                            </template>
                            <template #title>{{ $t("craftable-pro", "Delete Sale Return") }}</template>
                            <template #content>{{ $t("craftable-pro", "Are you sure? This action cannot be undone.") }}</template>
                            <template #buttons="{ setIsOpen }">
                                <Button @click.prevent="() => { action('delete', route('craftable-pro.sale-returns.destroy', item), { onFinish: () => setIsOpen(false) }); }" color="danger" v-can="'craftable-pro.sale-returns.destroy'">
                                    {{ $t("craftable-pro", "Delete") }}
                                </Button>
                                <Button @click.prevent="() => setIsOpen()" color="gray" variant="outline">{{ $t("craftable-pro", "Cancel") }}</Button>
                            </template>
                        </Modal>
                    </div>
                </ListingDataCell>
            </template>
        </Listing>
    </PageContent>
</template>

<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { PlusIcon, TrashIcon, PencilSquareIcon } from "@heroicons/vue/24/outline";
import {
    PageHeader, PageContent, Button, Listing,
    ListingHeaderCell, ListingDataCell, Modal, IconButton,
} from "craftable-pro/Components";
import { PaginatedCollection } from "craftable-pro/types/pagination";
import type { SaleReturn } from "./types";
import dayjs from "dayjs";

const doneClass = "bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-400";
const pendingClass = "bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400";
const warnClass = "bg-warning-50 text-warning-700 dark:bg-warning-500/10 dark:text-warning-400";

const refundLabel = (r: any) => {
    if (r.is_refunded) return "Refunded";
    if (r.is_refund_required) return "Refund due";
    return "None";
};
const refundClass = (r: any) => {
    if (r.is_refunded) return doneClass;
    if (r.is_refund_required) return warnClass;
    return pendingClass;
};

interface Props {
    saleReturns: PaginatedCollection<SaleReturn>;
}
defineProps<Props>();
</script>
