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
                <ListingHeaderCell sortBy='order_no'>{{ $t("craftable-pro", "Order No") }}</ListingHeaderCell>
                <ListingHeaderCell>{{ $t("craftable-pro", "Customer") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='latest_status'>{{ $t("craftable-pro", "Status") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='price'>{{ $t("craftable-pro", "Total") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='is_paid'>{{ $t("craftable-pro", "Paid") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='created_at'>{{ $t("craftable-pro", "Date") }}</ListingHeaderCell>
                <ListingHeaderCell><span class="sr-only">{{ $t("craftable-pro", "Actions") }}</span></ListingHeaderCell>
            </template>

            <template #tableRow="{ item, action }: any">
                <ListingDataCell>
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-500 dark:bg-white/5 dark:text-gray-300">
                            <ShoppingCartIcon class="h-5 w-5" />
                        </span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ item.order_no }}</span>
                    </div>
                </ListingDataCell>
                <ListingDataCell>
                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ customerName(item.customer) }}</span>
                </ListingDataCell>
                <ListingDataCell>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" :class="statusClass(item.latest_status)">
                        {{ item.latest_status || '—' }}
                    </span>
                </ListingDataCell>
                <ListingDataCell>
                    <span class="font-medium tabular-nums text-gray-900 dark:text-white">{{ money(item.price) }}</span>
                </ListingDataCell>
                <ListingDataCell>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" :class="item.is_paid ? paidClass : unpaidClass">
                        {{ item.is_paid ? $t("craftable-pro", "Paid") : $t("craftable-pro", "Unpaid") }}
                    </span>
                </ListingDataCell>
                <ListingDataCell>
                    <span class="text-sm text-gray-500">{{ item.created_at && dayjs(item.created_at).format('DD MMM YYYY') }}</span>
                </ListingDataCell>
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
import { PlusIcon, TrashIcon, PencilSquareIcon, EyeIcon, ShoppingCartIcon } from "@heroicons/vue/24/outline";
import {
    PageHeader, PageContent, Button, Listing,
    ListingHeaderCell, ListingDataCell, Modal, IconButton,
} from "craftable-pro/Components";
import { PaginatedCollection } from "craftable-pro/types/pagination";
import type { OrderHeader } from "./types";
import dayjs from "dayjs";

const money = (v: any) =>
    Number(v ?? 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + " DH";

const customerName = (c: any) =>
    !c ? "—" : (c.is_company ? c.company_name : `${c.first_name ?? ""} ${c.last_name ?? ""}`).trim() || "—";

const paidClass = "bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-400";
const unpaidClass = "bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400";

const statusClass = (s: string) => {
    const map: Record<string, string> = {
        Draft: "bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300",
        Submitted: "bg-info-50 text-info-700 dark:bg-info-500/10 dark:text-info-400",
        Approved: "bg-primary-50 text-primary-700 dark:bg-primary-500/10 dark:text-primary-400",
        Scheduled: "bg-secondary-50 text-secondary-700 dark:bg-secondary-500/10 dark:text-secondary-400",
        Ready: "bg-info-50 text-info-700 dark:bg-info-500/10 dark:text-info-400",
        Delivered: "bg-primary-50 text-primary-700 dark:bg-primary-500/10 dark:text-primary-400",
        Completed: "bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-400",
        Cancelled: "bg-danger-50 text-danger-700 dark:bg-danger-500/10 dark:text-danger-400",
    };
    return map[s] ?? "bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300";
};

interface Props {
    orderHeaders: PaginatedCollection<OrderHeader>;
}
defineProps<Props>();
</script>
