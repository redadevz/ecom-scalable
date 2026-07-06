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
                <ListingHeaderCell sortBy='id'>{{ $t("craftable-pro", "Purchase") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='created_at'>{{ $t("craftable-pro", "Date") }}</ListingHeaderCell>
                <ListingHeaderCell>{{ $t("craftable-pro", "Items") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='entry_stock_time'>{{ $t("craftable-pro", "Status") }}</ListingHeaderCell>
                <ListingHeaderCell><span class="sr-only">{{ $t("craftable-pro", "Actions") }}</span></ListingHeaderCell>
            </template>

            <template #tableRow="{ item, action }: any">
                <!-- Purchase: initials avatar + number + supplier -->
                <ListingDataCell>
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-lg bg-primary-500/10 text-sm font-bold uppercase text-primary-600 dark:text-primary-400">
                            {{ initials(item.id) }}
                        </span>
                        <div class="flex flex-col leading-tight">
                            <span class="font-medium text-gray-900 dark:text-white">{{ purchaseNumber(item.id) }}</span>
                            <span class="text-xs text-gray-400">{{ supplierName(item.supplier) }}</span>
                        </div>
                    </div>
                </ListingDataCell>

                <!-- Date -->
                <ListingDataCell>
                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ item.created_at ? dayjs(item.created_at).format('DD MMM YYYY') : '—' }}</span>
                </ListingDataCell>

                <!-- Items / line count -->
                <ListingDataCell>
                    <span class="text-sm text-gray-700 dark:text-gray-200">
                        {{ (item.purchase_items_count ?? 0) + ' ' + $t("craftable-pro", "items") }}
                    </span>
                </ListingDataCell>

                <!-- Status: Received (green) vs Pending (amber) -->
                <ListingDataCell>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" :class="item.entry_stock_time ? receivedClass : pendingClass">
                        {{ item.entry_stock_time ? $t("craftable-pro", "Received") : $t("craftable-pro", "Pending") }}
                    </span>
                </ListingDataCell>

                <!-- Actions -->
                <ListingDataCell>
                    <div class="flex items-center justify-center gap-2">
                        <Link :href="route('craftable-pro.purchases.edit', item)" title="View"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500 transition-colors hover:bg-gray-200 dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10">
                            <EyeIcon class="h-4 w-4" />
                        </Link>
                        <Link :href="route('craftable-pro.purchases.edit', item)" title="Edit" v-can="'craftable-pro.purchases.edit'"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary-600 transition-colors hover:bg-primary-100 dark:bg-primary-500/10 dark:text-primary-400 dark:hover:bg-primary-500/20">
                            <PencilSquareIcon class="h-4 w-4" />
                        </Link>
                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <button @click="() => setIsOpen(true)" title="Delete" v-can="'craftable-pro.purchases.destroy'"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 transition-colors hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20">
                                    <TrashIcon class="h-4 w-4" />
                                </button>
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
import { PlusIcon, TrashIcon, PencilSquareIcon, EyeIcon } from "@heroicons/vue/24/outline";
import {
    PageHeader, PageContent, Button, Listing,
    ListingHeaderCell, ListingDataCell, Modal,
} from "craftable-pro/Components";
import { PaginatedCollection } from "craftable-pro/types/pagination";
import type { Purchase } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat);

const purchaseNumber = (id: number | string) => `#${String(id).padStart(5, "0")}`;
const initials = (id: number | string) => String(id).slice(0, 2).padStart(2, "0");
const supplierName = (s: any) => (!s ? "—" : s.company_name || s.code || "—");

const receivedClass = "bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-400";
const pendingClass = "bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400";

interface Props {
    purchases: PaginatedCollection<Purchase>;
}
defineProps<Props>();
</script>
