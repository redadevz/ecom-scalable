<template>
    <PageHeader :title="$t('craftable-pro', 'Purchases')">
        <Button
            :leftIcon="PlusIcon"
            :as="Link"
            :href="route('craftable-pro.purchases.create')"
            v-can="'craftable-pro.purchases.create'"
        >
            {{ $t("craftable-pro", "New Purchase") }}
        </Button>
    </PageHeader>

    <PageContent>
        <Listing
            :baseUrl="route('craftable-pro.purchases.index')"
            :data="purchases"
            dataKey="purchases"
        >
            <template #bulkActions="{ bulkAction }">
                <Modal type="danger">
                    <template #trigger="{ setIsOpen }">
                        <Button @click="() => setIsOpen(true)" color="gray" variant="outline" size="sm" :leftIcon="TrashIcon" v-can="'craftable-pro.purchases.destroy'">
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                    </template>
                    <template #title>{{ $t("craftable-pro", "Delete Purchase") }}</template>
                    <template #content>{{ $t("craftable-pro", "Are you sure? This action cannot be undone.") }}</template>
                    <template #buttons="{ setIsOpen }">
                        <Button @click.prevent="() => { bulkAction('post', route('craftable-pro.purchases.bulk-destroy'), { onFinish: () => setIsOpen(false) }); }" color="danger" v-can="'craftable-pro.purchases.destroy'">
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                        <Button @click.prevent="() => setIsOpen()" color="gray" variant="outline">{{ $t("craftable-pro", "Cancel") }}</Button>
                    </template>
                </Modal>
            </template>

            <template #tableHead>
                <ListingHeaderCell>{{ $t("craftable-pro", "Supplier") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='description'>{{ $t("craftable-pro", "Description") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='is_paid'>{{ $t("craftable-pro", "Paid") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='entry_stock_time'>{{ $t("craftable-pro", "Stock Entry") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='created_at'>{{ $t("craftable-pro", "Created") }}</ListingHeaderCell>
                <ListingHeaderCell><span class="sr-only">{{ $t("craftable-pro", "Actions") }}</span></ListingHeaderCell>
            </template>

            <template #tableRow="{ item, action }: any">
                <ListingDataCell>
                    <span class="font-medium text-gray-900 dark:text-white">{{ supplierName(item.supplier) }}</span>
                </ListingDataCell>
                <ListingDataCell>
                    <span class="text-sm text-gray-500">{{ item.description || '—' }}</span>
                </ListingDataCell>
                <ListingDataCell>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" :class="item.is_paid ? paidClass : unpaidClass">
                        {{ item.is_paid ? $t("craftable-pro", "Paid") : $t("craftable-pro", "Unpaid") }}
                    </span>
                </ListingDataCell>
                <ListingDataCell>
                    <span class="text-sm text-gray-500">{{ item.entry_stock_time && dayjs(item.entry_stock_time).format('DD MMM YYYY') }}</span>
                </ListingDataCell>
                <ListingDataCell>
                    <span class="text-sm text-gray-500">{{ item.created_at && dayjs(item.created_at).format('DD MMM YYYY') }}</span>
                </ListingDataCell>
                <ListingDataCell>
                    <div class="flex items-center justify-end gap-3">
                        <IconButton :as="Link" :href="route('craftable-pro.purchases.edit', item)" variant="ghost" color="gray" :icon="PencilSquareIcon" v-can="'craftable-pro.purchases.edit'" />
                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <IconButton @click="() => setIsOpen(true)" color="gray" variant="ghost" :icon="TrashIcon" v-can="'craftable-pro.purchases.destroy'" />
                            </template>
                            <template #title>{{ $t("craftable-pro", "Delete Purchase") }}</template>
                            <template #content>{{ $t("craftable-pro", "Are you sure? This action cannot be undone.") }}</template>
                            <template #buttons="{ setIsOpen }">
                                <Button @click.prevent="() => { action('delete', route('craftable-pro.purchases.destroy', item), { onFinish: () => setIsOpen(false) }); }" color="danger" v-can="'craftable-pro.purchases.destroy'">
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
import type { Purchase } from "./types";
import dayjs from "dayjs";

const supplierName = (s: any) => !s ? "—" : (s.company_name || s.code || "—");

const paidClass = "bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-400";
const unpaidClass = "bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400";

interface Props {
    purchases: PaginatedCollection<Purchase>;
}
defineProps<Props>();
</script>
