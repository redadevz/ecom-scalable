<template>
    <PageHeader :title="$t('craftable-pro', 'Order Reviews')">
        <Button
            :leftIcon="PlusIcon"
            :as="Link"
            :href="route('craftable-pro.order-reviews.create')"
            v-can="'craftable-pro.order-reviews.create'"
        >
            {{ $t("craftable-pro", "New Order Review") }}
        </Button>
    </PageHeader>

    <PageContent>
        <Listing
            :baseUrl="route('craftable-pro.order-reviews.index')"
            :data="orderReviews"
            dataKey="orderReviews"
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
                            v-can="'craftable-pro.order-reviews.destroy'"
                        >
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                    </template>

                    <template #title>
                        {{ $t("craftable-pro", "Delete Order Review") }}
                    </template>

                    <template #content>
                        {{
                            $t(
                                "craftable-pro",
                                "Are you sure you want to delete selected Order Review? All data will be permanently removed from our servers forever. This action cannot be undone."
                            )
                        }}
                    </template>

                    <template #buttons="{ setIsOpen }">
                        <Button
                            @click.prevent="
                                () => {
                                    bulkAction('post', route('craftable-pro.order-reviews.bulk-destroy'), {
                                        onFinish: () => setIsOpen(false),
                                    });
                                }
                            "
                            color="danger"
                            v-can="'craftable-pro.order-reviews.destroy'"
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
                <ListingHeaderCell sortBy='order_id'>{{ $t("craftable-pro", "Order / Customer") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='rating'>{{ $t("craftable-pro", "Rating") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='review'>{{ $t("craftable-pro", "Review") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='review_time'>{{ $t("craftable-pro", "Reviewed") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='is_compensated'>{{ $t("craftable-pro", "Compensated") }}</ListingHeaderCell>
                <ListingHeaderCell><span class="sr-only">{{ $t("craftable-pro", "Actions") }}</span></ListingHeaderCell>
            </template>

            <template #tableRow="{ item, action }: any">
                <!-- Order / Customer: initials avatar + order_no + customer code -->
                <ListingDataCell>
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-primary-500/10 text-sm font-bold uppercase text-primary-600 dark:text-primary-400">
                            {{ initials(item) }}
                        </span>
                        <div class="flex flex-col leading-tight">
                            <span class="font-medium text-gray-900 dark:text-white">{{ item.order?.order_no ? '#' + item.order.order_no : ('#' + item.order_id) }}</span>
                            <span class="text-xs text-gray-400">{{ item.customer?.code || ('Customer ' + item.customer_id) }}</span>
                        </div>
                    </div>
                </ListingDataCell>

                <!-- Rating: stars -->
                <ListingDataCell>
                    <div class="flex items-center gap-1.5">
                        <div class="flex items-center gap-0.5">
                            <StarIcon
                                v-for="n in 5"
                                :key="n"
                                class="h-4 w-4"
                                :class="n <= Number(item.rating) ? 'text-amber-400' : 'text-gray-200 dark:text-white/10'"
                            />
                        </div>
                        <span class="text-xs font-medium text-gray-500">{{ Number(item.rating) }}/5</span>
                    </div>
                </ListingDataCell>

                <!-- Review text truncated -->
                <ListingDataCell>
                    <span class="block max-w-[280px] truncate text-sm text-gray-600 dark:text-gray-300" :title="item.review">{{ item.review || '—' }}</span>
                </ListingDataCell>

                <!-- Reviewed date -->
                <ListingDataCell>
                    <span class="text-sm text-gray-500">{{ item.review_time ? dayjs(item.review_time).format('DD MMM YYYY') : '—' }}</span>
                </ListingDataCell>

                <!-- Compensated toggle -->
                <ListingDataCell>
                    <ListingToggle
                        name="is_compensated"
                        v-model="item.is_compensated"
                        :updateUrl="route('craftable-pro.order-reviews.update', item.id)"
                    />
                </ListingDataCell>

                <!-- Actions: rounded icon buttons (Larkon) -->
                <ListingDataCell>
                    <div class="flex items-center justify-center gap-2">
                        <Link :href="route('craftable-pro.order-reviews.edit', item)" title="View"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500 transition-colors hover:bg-gray-200 dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10">
                            <EyeIcon class="h-4 w-4" />
                        </Link>
                        <Link :href="route('craftable-pro.order-reviews.edit', item)" title="Edit" v-can="'craftable-pro.order-reviews.edit'"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary-600 transition-colors hover:bg-primary-100 dark:bg-primary-500/10 dark:text-primary-400 dark:hover:bg-primary-500/20">
                            <PencilSquareIcon class="h-4 w-4" />
                        </Link>
                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <button @click="() => setIsOpen(true)" title="Delete" v-can="'craftable-pro.order-reviews.destroy'"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 transition-colors hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20">
                                    <TrashIcon class="h-4 w-4" />
                                </button>
                            </template>
                            <template #title>{{ $t("craftable-pro", "Delete Order Review") }}</template>
                            <template #content>
                                {{ $t("craftable-pro", "Are you sure you want to delete selected Order Review? All data will be permanently removed from our servers forever. This action cannot be undone.") }}
                            </template>
                            <template #buttons="{ setIsOpen }">
                                <Button @click.prevent="() => { action('delete', route('craftable-pro.order-reviews.destroy', item), { onFinish: () => setIsOpen(false) }); }" color="danger" v-can="'craftable-pro.order-reviews.destroy'">
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
import { StarIcon } from "@heroicons/vue/24/solid";
import {
    PageHeader,
    PageContent,
    Button,
    Listing,
    ListingHeaderCell,
    ListingDataCell,
    Modal,
    ListingToggle,
} from "craftable-pro/Components";
import { PaginatedCollection } from "craftable-pro/types/pagination";
import type { OrderReview } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat)

interface Props {
    orderReviews: PaginatedCollection<OrderReview>;
}
defineProps<Props>();

const initials = (item: any) => {
    const base = (item.customer?.code || item.order?.order_no || "OR").toString().trim();
    return base.slice(0, 2).toUpperCase();
};
</script>
