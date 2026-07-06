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
                        <Button
                            @click="() => setIsOpen(true)"
                            color="gray"
                            variant="outline"
                            size="sm"
                            :leftIcon="TrashIcon"
                            v-can="'craftable-pro.sale-returns.destroy'"
                        >
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                    </template>

                    <template #title>
                        {{ $t("craftable-pro", "Delete Sale Return") }}
                    </template>

                    <template #content>
                        {{
                            $t(
                                "craftable-pro",
                                "Are you sure you want to delete selected Sale Return? All data will be permanently removed from our servers forever. This action cannot be undone."
                            )
                        }}
                    </template>

                    <template #buttons="{ setIsOpen }">
                        <Button
                            @click.prevent="
                                () => {
                                    bulkAction('post', route('craftable-pro.sale-returns.bulk-destroy'), {
                                        onFinish: () => setIsOpen(false),
                                    });
                                }
                            "
                            color="danger"
                            v-can="'craftable-pro.sale-returns.destroy'"
                        >
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                        <Button
                            @click.prevent="() => setIsOpen()"
                            color="gray"
                            variant="outline"
                        >
                            {{ $t("craftable-pro", "Cancel") }}
                        </Button>
                    </template>
                </Modal>
            </template>

            <template #tableHead>
                <ListingHeaderCell sortBy='id'>{{ $t("craftable-pro", "Return") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='created_at'>{{ $t("craftable-pro", "Date") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='refund_amount'>{{ $t("craftable-pro", "Amount") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='entry_stock_time'>{{ $t("craftable-pro", "Status") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='is_refunded'>{{ $t("craftable-pro", "Refund") }}</ListingHeaderCell>
                <ListingHeaderCell><span class="sr-only">{{ $t("craftable-pro", "Actions") }}</span></ListingHeaderCell>
            </template>

            <template #tableRow="{ item, action }: any">
                <!-- Return: initials avatar + reference + related order -->
                <ListingDataCell>
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-primary-500/10 text-sm font-bold uppercase text-primary-600 dark:text-primary-400">
                            {{ initials(item) }}
                        </span>
                        <div class="flex flex-col leading-tight">
                            <span class="font-medium text-gray-900 dark:text-white">{{ $t("craftable-pro", "Return") }} #{{ item.id }}</span>
                            <span class="text-xs text-gray-400">{{ item.order?.order_no ? 'Order ' + item.order.order_no : '—' }}</span>
                        </div>
                    </div>
                </ListingDataCell>

                <!-- Date -->
                <ListingDataCell>
                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ item.created_at ? dayjs(item.created_at).format('DD MMM YYYY') : '—' }}</span>
                </ListingDataCell>

                <!-- Refund amount -->
                <ListingDataCell>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ money(item.refund_amount) }}</span>
                </ListingDataCell>

                <!-- Status: Processed vs Pending -->
                <ListingDataCell>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" :class="item.entry_stock_time ? doneClass : pendingClass">
                        {{ item.entry_stock_time ? $t("craftable-pro", "Processed") : $t("craftable-pro", "Pending") }}
                    </span>
                </ListingDataCell>

                <!-- Refund status -->
                <ListingDataCell>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" :class="refundClass(item)">
                        {{ refundLabel(item) }}
                    </span>
                </ListingDataCell>

                <!-- Actions: rounded icon buttons (Larkon) -->
                <ListingDataCell>
                    <div class="flex items-center justify-center gap-2">
                        <Link :href="route('craftable-pro.sale-returns.edit', item)" title="View"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500 transition-colors hover:bg-gray-200 dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10">
                            <EyeIcon class="h-4 w-4" />
                        </Link>
                        <Link :href="route('craftable-pro.sale-returns.edit', item)" title="Edit" v-can="'craftable-pro.sale-returns.edit'"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary-600 transition-colors hover:bg-primary-100 dark:bg-primary-500/10 dark:text-primary-400 dark:hover:bg-primary-500/20">
                            <PencilSquareIcon class="h-4 w-4" />
                        </Link>
                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <button @click="() => setIsOpen(true)" title="Delete" v-can="'craftable-pro.sale-returns.destroy'"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 transition-colors hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20">
                                    <TrashIcon class="h-4 w-4" />
                                </button>
                            </template>
                            <template #title>{{ $t("craftable-pro", "Delete Sale Return") }}</template>
                            <template #content>
                                {{ $t("craftable-pro", "Are you sure you want to delete selected Sale Return? All data will be permanently removed from our servers forever. This action cannot be undone.") }}
                            </template>
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
import {
    PlusIcon,
    TrashIcon,
    PencilSquareIcon,
    EyeIcon,
} from "@heroicons/vue/24/outline";
import {
    PageHeader,
    PageContent,
    Button,
    Listing,
    ListingHeaderCell,
    ListingDataCell,
    Modal,
} from "craftable-pro/Components";
import { PaginatedCollection } from "craftable-pro/types/pagination";
import type { SaleReturn } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat);

const doneClass = "bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-400";
const pendingClass = "bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400";
const warnClass = "bg-warning-50 text-warning-700 dark:bg-warning-500/10 dark:text-warning-400";

const money = (v: any) =>
    Number(v ?? 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + " DH";

const initials = (r: any) => ("SR" + String(r?.id ?? "")).slice(0, 2).toUpperCase();

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
