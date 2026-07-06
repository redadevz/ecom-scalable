<template>
    <PageHeader :title="$t('craftable-pro', 'Order Line Discounts')">
        <Button
            :leftIcon="PlusIcon"
            :as="Link"
            :href="route('craftable-pro.order-line-discounts.create')"
            v-can="'craftable-pro.order-line-discounts.create'"
        >
            {{ $t("craftable-pro", "New Order Line Discount") }}
        </Button>
    </PageHeader>

    <PageContent>
        <Listing
            :baseUrl="route('craftable-pro.order-line-discounts.index')"
            :data="orderLineDiscounts"
            dataKey="orderLineDiscounts"
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
                            v-can="'craftable-pro.order-line-discounts.destroy'"
                        >
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                    </template>

                    <template #title>
                        {{ $t("craftable-pro", "Delete Order Line Discount") }}
                    </template>

                    <template #content>
                        {{
                            $t(
                                "craftable-pro",
                                "Are you sure you want to delete selected Order Line Discount? All data will be permanently removed from our servers forever. This action cannot be undone."
                            )
                        }}
                    </template>

                    <template #buttons="{ setIsOpen }">
                        <Button
                            @click.prevent="
                                () => {
                                    bulkAction('post', route('craftable-pro.order-line-discounts.bulk-destroy'), {
                                        onFinish: () => setIsOpen(false),
                                    });
                                }
                            "
                            color="danger"
                            v-can="'craftable-pro.order-line-discounts.destroy'"
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
                <ListingHeaderCell sortBy='discount_id'>{{ $t("craftable-pro", "Discount") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='order_line_id'>{{ $t("craftable-pro", "Order Line") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='value'>{{ $t("craftable-pro", "Value") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='comments'>{{ $t("craftable-pro", "Comments") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='created_at'>{{ $t("craftable-pro", "Created At") }}</ListingHeaderCell>
                <ListingHeaderCell><span class="sr-only">{{ $t("craftable-pro", "Actions") }}</span></ListingHeaderCell>
            </template>

            <template #tableRow="{ item, action }: any">
                <!-- Discount: initials avatar + description + type -->
                <ListingDataCell>
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-primary-500/10 text-sm font-bold uppercase text-primary-600 dark:text-primary-400">
                            {{ discountInitials(item) }}
                        </span>
                        <div class="flex flex-col leading-tight">
                            <span class="font-medium text-gray-900 dark:text-white">{{ item.discount?.description || ('#' + item.discount_id) }}</span>
                            <span class="text-xs text-gray-400">{{ item.discount?.discount_type?.name || '—' }}</span>
                        </div>
                    </div>
                </ListingDataCell>

                <!-- Order Line: line no + description -->
                <ListingDataCell>
                    <div class="flex flex-col leading-tight">
                        <span class="text-sm text-gray-900 dark:text-white">{{ item.order_line?.line_no ? 'Line #' + item.order_line.line_no : '#' + item.order_line_id }}</span>
                        <span class="block max-w-[220px] truncate text-xs text-gray-400" :title="item.order_line?.description">{{ item.order_line?.description || '' }}</span>
                    </div>
                </ListingDataCell>

                <!-- Value: percentage pill if percentage discount, else money -->
                <ListingDataCell>
                    <span
                        class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold"
                        :class="item.discount?.discount_type?.is_percentage
                            ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400'
                            : 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400'"
                    >
                        {{ formatValue(item) }}
                    </span>
                </ListingDataCell>

                <!-- Comments -->
                <ListingDataCell>
                    <span class="block max-w-[240px] truncate text-sm text-gray-600 dark:text-gray-300" :title="item.comments">{{ item.comments || '—' }}</span>
                </ListingDataCell>

                <!-- Created At -->
                <ListingDataCell>
                    <span class="text-sm text-gray-500">{{ item.created_at && dayjs(item.created_at).format('DD MMM YYYY') }}</span>
                </ListingDataCell>

                <!-- Actions: rounded icon buttons (Larkon) -->
                <ListingDataCell>
                    <div class="flex items-center justify-center gap-2">
                        <Link :href="route('craftable-pro.order-line-discounts.edit', item)" title="View"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500 transition-colors hover:bg-gray-200 dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10">
                            <EyeIcon class="h-4 w-4" />
                        </Link>
                        <Link :href="route('craftable-pro.order-line-discounts.edit', item)" title="Edit" v-can="'craftable-pro.order-line-discounts.edit'"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary-600 transition-colors hover:bg-primary-100 dark:bg-primary-500/10 dark:text-primary-400 dark:hover:bg-primary-500/20">
                            <PencilSquareIcon class="h-4 w-4" />
                        </Link>
                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <button @click="() => setIsOpen(true)" title="Delete" v-can="'craftable-pro.order-line-discounts.destroy'"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 transition-colors hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20">
                                    <TrashIcon class="h-4 w-4" />
                                </button>
                            </template>
                            <template #title>{{ $t("craftable-pro", "Delete Order Line Discount") }}</template>
                            <template #content>
                                {{ $t("craftable-pro", "Are you sure you want to delete selected Order Line Discount? All data will be permanently removed from our servers forever. This action cannot be undone.") }}
                            </template>
                            <template #buttons="{ setIsOpen }">
                                <Button @click.prevent="() => { action('delete', route('craftable-pro.order-line-discounts.destroy', item), { onFinish: () => setIsOpen(false) }); }" color="danger" v-can="'craftable-pro.order-line-discounts.destroy'">
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
import type { OrderLineDiscount } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat)

interface Props {
    orderLineDiscounts: PaginatedCollection<OrderLineDiscount>;
}
defineProps<Props>();

const discountInitials = (item: any): string => {
    const base = (item.discount?.description || '').toString().trim();
    const parts = base.split(/\s+/).filter(Boolean);
    if (!parts.length) return ('#' + (item.discount_id ?? '?')).toString().slice(0, 2).toUpperCase();
    return (parts[0][0] + (parts[1]?.[0] ?? '')).toUpperCase();
};

const formatValue = (item: any): string => {
    const v = Number(item.value ?? 0);
    if (item.discount?.discount_type?.is_percentage) {
        return v.toLocaleString(undefined, { maximumFractionDigits: 2 }) + '%';
    }
    return v.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};
</script>
