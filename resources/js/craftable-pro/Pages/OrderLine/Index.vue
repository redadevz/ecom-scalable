<template>
    <PageHeader :title="$t('craftable-pro', 'Order Lines')">
        <Button
            :leftIcon="PlusIcon"
            :as="Link"
            :href="route('craftable-pro.order-lines.create')"
            v-can="'craftable-pro.order-lines.create'"
        >
            {{ $t("craftable-pro", "New Order Line") }}
        </Button>
    </PageHeader>

    <PageContent>
        <Listing
            :baseUrl="route('craftable-pro.order-lines.index')"
            :data="orderLines"
            dataKey="orderLines"
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
                            v-can="'craftable-pro.order-lines.destroy'"
                        >
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                    </template>

                    <template #title>
                        {{ $t("craftable-pro", "Delete Order Line") }}
                    </template>

                    <template #content>
                        {{
                            $t(
                                "craftable-pro",
                                "Are you sure you want to delete selected Order Line? All data will be permanently removed from our servers forever. This action cannot be undone."
                            )
                        }}
                    </template>

                    <template #buttons="{ setIsOpen }">
                        <Button
                            @click.prevent="
                                () => {
                                    bulkAction('post', route('craftable-pro.order-lines.bulk-destroy'), {
                                        onFinish: () => setIsOpen(false),
                                    });
                                }
                            "
                            color="danger"
                            v-can="'craftable-pro.order-lines.destroy'"
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
                <ListingHeaderCell sortBy='item_id'>{{ $t("craftable-pro", "Item") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='order_id'>{{ $t("craftable-pro", "Order") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='quantity'>{{ $t("craftable-pro", "Quantity") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='price'>{{ $t("craftable-pro", "Unit Price") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='price_after_tax'>{{ $t("craftable-pro", "Line Total") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='is_canceled'>{{ $t("craftable-pro", "Status") }}</ListingHeaderCell>
                <ListingHeaderCell><span class="sr-only">{{ $t("craftable-pro", "Actions") }}</span></ListingHeaderCell>
            </template>

            <template #tableRow="{ item, action }: any">
                <!-- Item: initials avatar + item name + line no -->
                <ListingDataCell>
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-primary-500/10 text-sm font-bold uppercase text-primary-600 dark:text-primary-400">
                            {{ initials(item.item?.name) }}
                        </span>
                        <div class="flex flex-col">
                            <span class="font-medium text-gray-900 dark:text-white">{{ item.item?.name || '—' }}</span>
                            <span class="text-xs text-gray-400">{{ item.line_no ? '#' + item.line_no : '' }}</span>
                        </div>
                    </div>
                </ListingDataCell>

                <!-- Order reference -->
                <ListingDataCell>
                    <div class="flex flex-col leading-tight">
                        <span class="text-sm text-gray-900 dark:text-white">{{ item.order?.order_no || '—' }}</span>
                        <span class="max-w-[200px] truncate text-xs text-gray-400" :title="item.description">{{ item.description }}</span>
                    </div>
                </ListingDataCell>

                <!-- Quantity -->
                <ListingDataCell>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ item.quantity ?? '—' }}</span>
                </ListingDataCell>

                <!-- Unit price -->
                <ListingDataCell>
                    <span class="text-sm text-gray-700 dark:text-gray-200">{{ money(item.price) }}</span>
                </ListingDataCell>

                <!-- Line total (after tax) -->
                <ListingDataCell>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ money(item.price_after_tax) }}</span>
                </ListingDataCell>

                <!-- Status pill -->
                <ListingDataCell>
                    <span v-if="item.is_canceled" class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-0.5 text-xs font-medium text-red-700 dark:bg-red-500/10 dark:text-red-400" :title="item.canceled_time ? dayjs(item.canceled_time).format('DD MMM YYYY') : ''">
                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                        {{ $t("craftable-pro", "Canceled") }}
                    </span>
                    <span v-else class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700 dark:bg-green-500/10 dark:text-green-400">
                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                        {{ $t("craftable-pro", "Active") }}
                    </span>
                </ListingDataCell>

                <!-- Actions: rounded icon buttons (Larkon) -->
                <ListingDataCell>
                    <div class="flex items-center justify-center gap-2">
                        <Link :href="route('craftable-pro.order-lines.edit', item)" title="View"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500 transition-colors hover:bg-gray-200 dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10">
                            <EyeIcon class="h-4 w-4" />
                        </Link>
                        <Link :href="route('craftable-pro.order-lines.edit', item)" title="Edit" v-can="'craftable-pro.order-lines.edit'"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary-600 transition-colors hover:bg-primary-100 dark:bg-primary-500/10 dark:text-primary-400 dark:hover:bg-primary-500/20">
                            <PencilSquareIcon class="h-4 w-4" />
                        </Link>
                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <button @click="() => setIsOpen(true)" title="Delete" v-can="'craftable-pro.order-lines.destroy'"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 transition-colors hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20">
                                    <TrashIcon class="h-4 w-4" />
                                </button>
                            </template>
                            <template #title>{{ $t("craftable-pro", "Delete Order Line") }}</template>
                            <template #content>
                                {{ $t("craftable-pro", "Are you sure you want to delete selected Order Line? All data will be permanently removed from our servers forever. This action cannot be undone.") }}
                            </template>
                            <template #buttons="{ setIsOpen }">
                                <Button @click.prevent="() => { action('delete', route('craftable-pro.order-lines.destroy', item), { onFinish: () => setIsOpen(false) }); }" color="danger" v-can="'craftable-pro.order-lines.destroy'">
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
import type { OrderLine } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat);

interface Props {
    orderLines: PaginatedCollection<OrderLine>;
}
defineProps<Props>();

const money = (v: any) =>
    Number(v ?? 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + " DH";

const initials = (name?: string | null) => {
    const parts = (name || "").toString().trim().split(/\s+/).filter(Boolean);
    if (!parts.length) return "?";
    return (parts[0][0] + (parts[1]?.[0] ?? "")).toUpperCase();
};
</script>
