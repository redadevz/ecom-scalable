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
                <ListingHeaderCell sortBy='replied_by'>
                    {{ $t("craftable-pro", "Replied By") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='rating'>
                    {{ $t("craftable-pro", "Rating") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='review'>
                    {{ $t("craftable-pro", "Review") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='review_time'>
                    {{ $t("craftable-pro", "Review Time") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='reply'>
                    {{ $t("craftable-pro", "Reply") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='reply_time'>
                    {{ $t("craftable-pro", "Reply Time") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='is_compensated'>
                    {{ $t("craftable-pro", "Is Compensated") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='compensation_value'>
                    {{ $t("craftable-pro", "Compensation Value") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='comments'>
                    {{ $t("craftable-pro", "Comments") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='created_at'>
                    {{ $t("craftable-pro", "Created At") }}
                </ListingHeaderCell>
                <ListingHeaderCell>
                    <span class="sr-only">{{ $t("craftable-pro", "Actions") }}</span>
                </ListingHeaderCell>
            </template>
            <template #tableRow="{ item, action }: any">
                <ListingDataCell>
                    <span class="font-medium text-gray-900 dark:text-white">{{ item.replied_by }}</span>
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="rating"
                        v-model="item.rating"
                        :updateUrl="route('craftable-pro.order-reviews.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.review }}
                </ListingDataCell>
                <ListingDataCell>
                    <span class="text-sm text-gray-500">{{ item.review_time && dayjs(item.review_time).format('DD MMM YYYY') }}</span>
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.reply }}
                </ListingDataCell>
                <ListingDataCell>
                    <span class="text-sm text-gray-500">{{ item.reply_time && dayjs(item.reply_time).format('DD MMM YYYY') }}</span>
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="is_compensated"
                        v-model="item.is_compensated"
                        :updateUrl="route('craftable-pro.order-reviews.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.compensation_value }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.comments }}
                </ListingDataCell>
                <ListingDataCell>
                    <span class="text-sm text-gray-500">{{ item.created_at && dayjs(item.created_at).format('DD MMM YYYY') }}</span>
                </ListingDataCell>
                <ListingDataCell>
                    <div class="flex items-center justify-end gap-3">
                        <IconButton
                            :as="Link"
                            :href="route('craftable-pro.order-reviews.edit', item)"
                            variant="ghost"
                            color="gray"
                            :icon="PencilSquareIcon"
                            v-can="'craftable-pro.order-reviews.edit'"
                        />

                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <IconButton
                                    @click="() => setIsOpen(true)"
                                    color="gray"
                                    variant="ghost"
                                    :icon="TrashIcon"
                                    v-can="'craftable-pro.order-reviews.destroy'"
                                />
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
                                            action('delete', route('craftable-pro.order-reviews.destroy', item), {
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
                    </div>
                </ListingDataCell>
            </template>
        </Listing>
    </PageContent>
</template>

<script setup lang="ts">
import { Link, usePage } from "@inertiajs/vue3";
import {
    PlusIcon,
    TrashIcon,
    PencilSquareIcon,
    ArrowDownTrayIcon,
} from "@heroicons/vue/24/outline";
import {
    PageHeader,
    PageContent,
    Button,
    Listing,
    Avatar,
    ListingHeaderCell,
    ListingDataCell,
    Modal,
    Multiselect,
    IconButton,
    FiltersDropdown,
    Publish,
    ListingToggle,
} from "craftable-pro/Components";
import { PaginatedCollection } from "craftable-pro/types/pagination";
import type { OrderReview } from "./types";
import type { PageProps } from "craftable-pro/types/page";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat)



interface Props {
    orderReviews: PaginatedCollection<OrderReview>;
}
defineProps<Props>();

</script>
