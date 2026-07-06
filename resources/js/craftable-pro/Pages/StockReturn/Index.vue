<template>
    <PageHeader :title="$t('craftable-pro', 'Stock Returns')">
        <Button
            :leftIcon="PlusIcon"
            :as="Link"
            :href="route('craftable-pro.stock-returns.create')"
            v-can="'craftable-pro.stock-returns.create'"
        >
            {{ $t("craftable-pro", "New Stock Return") }}
        </Button>
    </PageHeader>

    <PageContent>
        <Listing
            :baseUrl="route('craftable-pro.stock-returns.index')"
            :data="stockReturns"
            dataKey="stockReturns"
        >
            <template #bulkActions="{ bulkAction }">
                <Modal type="danger">
                    <template #trigger="{ setIsOpen }">
                        <Button @click="() => setIsOpen(true)" color="gray" variant="outline" size="sm" :leftIcon="TrashIcon" v-can="'craftable-pro.stock-returns.destroy'">
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                    </template>
                    <template #title>{{ $t("craftable-pro", "Delete Stock Return") }}</template>
                    <template #content>{{ $t("craftable-pro", "Are you sure? This action cannot be undone.") }}</template>
                    <template #buttons="{ setIsOpen }">
                        <Button @click.prevent="() => { bulkAction('post', route('craftable-pro.stock-returns.bulk-destroy'), { onFinish: () => setIsOpen(false) }); }" color="danger" v-can="'craftable-pro.stock-returns.destroy'">
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                        <Button @click.prevent="() => setIsOpen()" color="gray" variant="outline">{{ $t("craftable-pro", "Cancel") }}</Button>
                    </template>
                </Modal>
            </template>

            <template #tableHead>
                <ListingHeaderCell sortBy='id'>{{ $t("craftable-pro", "Return") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='created_at'>{{ $t("craftable-pro", "Date") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='description'>{{ $t("craftable-pro", "Description") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='exit_stock_time'>{{ $t("craftable-pro", "Status") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='is_paid'>{{ $t("craftable-pro", "Paid") }}</ListingHeaderCell>
                <ListingHeaderCell><span class="sr-only">{{ $t("craftable-pro", "Actions") }}</span></ListingHeaderCell>
            </template>

            <template #tableRow="{ item, action }: any">
                <!-- Return: initials avatar + reference + supplier -->
                <ListingDataCell>
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-primary-500/10 text-sm font-bold uppercase text-primary-600 dark:text-primary-400">
                            {{ ('SR' + item.id).slice(0, 2) }}
                        </span>
                        <div class="flex flex-col leading-tight">
                            <span class="font-medium text-gray-900 dark:text-white">#{{ item.id }}</span>
                            <span class="text-xs text-gray-400">{{ item.purchase?.supplier?.company_name ?? item.purchase?.supplier?.code ?? '—' }}</span>
                        </div>
                    </div>
                </ListingDataCell>

                <!-- Date -->
                <ListingDataCell>
                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ item.created_at ? dayjs(item.created_at).format('DD MMM YYYY') : '—' }}</span>
                </ListingDataCell>

                <!-- Description -->
                <ListingDataCell>
                    <span class="block max-w-[240px] truncate text-sm text-gray-500" :title="item.description">{{ item.description || '—' }}</span>
                </ListingDataCell>

                <!-- Status: Processed vs Pending -->
                <ListingDataCell>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" :class="item.exit_stock_time ? doneClass : pendingClass">
                        {{ item.exit_stock_time ? $t("craftable-pro", "Processed") : $t("craftable-pro", "Pending") }}
                    </span>
                </ListingDataCell>

                <!-- Paid -->
                <ListingDataCell>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" :class="item.is_paid ? doneClass : pendingClass">
                        {{ item.is_paid ? $t("craftable-pro", "Paid") : $t("craftable-pro", "Unpaid") }}
                    </span>
                </ListingDataCell>

                <!-- Actions -->
                <ListingDataCell>
                    <div class="flex items-center justify-center gap-2">
                        <Link :href="route('craftable-pro.stock-returns.edit', item)" title="View"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500 transition-colors hover:bg-gray-200 dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10">
                            <EyeIcon class="h-4 w-4" />
                        </Link>
                        <Link :href="route('craftable-pro.stock-returns.edit', item)" title="Edit" v-can="'craftable-pro.stock-returns.edit'"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary-600 transition-colors hover:bg-primary-100 dark:bg-primary-500/10 dark:text-primary-400 dark:hover:bg-primary-500/20">
                            <PencilSquareIcon class="h-4 w-4" />
                        </Link>
                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <button @click="() => setIsOpen(true)" title="Delete" v-can="'craftable-pro.stock-returns.destroy'"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 transition-colors hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20">
                                    <TrashIcon class="h-4 w-4" />
                                </button>
                            </template>
                            <template #title>{{ $t("craftable-pro", "Delete Stock Return") }}</template>
                            <template #content>{{ $t("craftable-pro", "Are you sure? This action cannot be undone.") }}</template>
                            <template #buttons="{ setIsOpen }">
                                <Button @click.prevent="() => { action('delete', route('craftable-pro.stock-returns.destroy', item), { onFinish: () => setIsOpen(false) }); }" color="danger" v-can="'craftable-pro.stock-returns.destroy'">
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
import type { StockReturn } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat);

const doneClass = "bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-400";
const pendingClass = "bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400";

interface Props {
    stockReturns: PaginatedCollection<StockReturn>;
}
defineProps<Props>();
</script>
