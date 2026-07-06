<template>
    <PageHeader :title="$t('craftable-pro', 'Orders')">
        <Button
            :leftIcon="PlusIcon"
            :as="Link"
            :href="route('craftable-pro.order-headers.create')"
            v-can="'craftable-pro.order-headers.create'"
        >
            {{ $t("craftable-pro", "New Order") }}
        </Button>
    </PageHeader>

    <PageContent>
        <Listing
            :baseUrl="route('craftable-pro.order-headers.index')"
            :data="orderHeaders"
            dataKey="orderHeaders"
        >
            <template #bulkActions="{ bulkAction }">
                <Modal type="danger">
                    <template #trigger="{ setIsOpen }">
                        <Button @click="() => setIsOpen(true)" color="gray" variant="outline" size="sm" :leftIcon="TrashIcon" v-can="'craftable-pro.order-headers.destroy'">
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                    </template>
                    <template #title>{{ $t("craftable-pro", "Delete Order") }}</template>
                    <template #content>{{ $t("craftable-pro", "Are you sure? This action cannot be undone.") }}</template>
                    <template #buttons="{ setIsOpen }">
                        <Button @click.prevent="() => { bulkAction('post', route('craftable-pro.order-headers.bulk-destroy'), { onFinish: () => setIsOpen(false) }); }" color="danger" v-can="'craftable-pro.order-headers.destroy'">
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                        <Button @click.prevent="() => setIsOpen()" color="gray" variant="outline">{{ $t("craftable-pro", "Cancel") }}</Button>
                    </template>
                </Modal>
            </template>

            <template #tableHead>
                <ListingHeaderCell sortBy='order_no'>{{ $t("craftable-pro", "Order") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='created_at'>{{ $t("craftable-pro", "Date") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='price'>{{ $t("craftable-pro", "Total") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='latest_status'>{{ $t("craftable-pro", "Status") }}</ListingHeaderCell>
                <ListingHeaderCell><span class="sr-only">{{ $t("craftable-pro", "Actions") }}</span></ListingHeaderCell>
            </template>

            <template #tableRow="{ item, action }: any">
                <!-- Order: initials avatar + order no + customer -->
                <ListingDataCell>
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-primary-500/10 text-sm font-bold uppercase text-primary-600 dark:text-primary-400">
                            {{ (item.order_no || '?').slice(0, 2) }}
                        </span>
                        <div class="flex flex-col">
                            <span class="font-medium text-gray-900 dark:text-white">{{ item.order_no }}</span>
                            <span class="text-xs text-gray-400">{{ customerName(item.customer) }}</span>
                        </div>
                    </div>
                </ListingDataCell>

                <!-- Date -->
                <ListingDataCell>
                    <span class="text-sm text-gray-500">{{ item.created_at ? dayjs(item.created_at).format('DD MMM YYYY') : '—' }}</span>
                </ListingDataCell>

                <!-- Total -->
                <ListingDataCell>
                    <span class="font-medium tabular-nums text-gray-900 dark:text-white">{{ money(item.price) }}</span>
                </ListingDataCell>

                <!-- Status pill -->
                <ListingDataCell>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" :class="statusClass(item.latest_status)">
                        {{ item.latest_status || '—' }}
                    </span>
                </ListingDataCell>

                <!-- Actions: rounded icon buttons (Larkon) -->
                <ListingDataCell>
                    <div class="flex items-center justify-center gap-2">
                        <Link :href="route('craftable-pro.order-headers.edit', item)" title="View"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500 transition-colors hover:bg-gray-200 dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10">
                            <EyeIcon class="h-4 w-4" />
                        </Link>
                        <Link :href="route('craftable-pro.order-headers.edit', item)" title="Edit" v-can="'craftable-pro.order-headers.edit'"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary-600 transition-colors hover:bg-primary-100 dark:bg-primary-500/10 dark:text-primary-400 dark:hover:bg-primary-500/20">
                            <PencilSquareIcon class="h-4 w-4" />
                        </Link>
                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <button @click="() => setIsOpen(true)" title="Delete" v-can="'craftable-pro.order-headers.destroy'"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 transition-colors hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20">
                                    <TrashIcon class="h-4 w-4" />
                                </button>
                            </template>
                            <template #title>{{ $t("craftable-pro", "Delete Order") }}</template>
                            <template #content>{{ $t("craftable-pro", "Are you sure? This action cannot be undone.") }}</template>
                            <template #buttons="{ setIsOpen }">
                                <Button @click.prevent="() => { action('delete', route('craftable-pro.order-headers.destroy', item), { onFinish: () => setIsOpen(false) }); }" color="danger" v-can="'craftable-pro.order-headers.destroy'">
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
import type { OrderHeader } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat);

const money = (v: any) =>
    Number(v ?? 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + " DH";

const customerName = (c: any) =>
    !c ? "—" : (c.is_company ? c.company_name : `${c.first_name ?? ""} ${c.last_name ?? ""}`).trim() || "—";

const statusClass = (s: string) => {
    const status = (s ?? "").toLowerCase();
    if (status.includes("cancel")) {
        return "bg-danger-50 text-danger-700 dark:bg-danger-500/10 dark:text-danger-400";
    }
    if (status.includes("approv") || status.includes("invoic")) {
        return "bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-400";
    }
    return "bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300";
};

interface Props {
    orderHeaders: PaginatedCollection<OrderHeader>;
}
defineProps<Props>();
</script>
