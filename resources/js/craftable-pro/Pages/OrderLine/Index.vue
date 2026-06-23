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
                <ListingHeaderCell sortBy='id'>
                    {{ $t("craftable-pro", "Id") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='store_id'>
                    {{ $t("craftable-pro", "Store Id") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='order_id'>
                    {{ $t("craftable-pro", "Order Id") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='item_id'>
                    {{ $t("craftable-pro", "Item Id") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='line_no'>
                    {{ $t("craftable-pro", "Line No") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='description'>
                    {{ $t("craftable-pro", "Description") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='customer_notes'>
                    {{ $t("craftable-pro", "Customer Notes") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='quantity'>
                    {{ $t("craftable-pro", "Quantity") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='current_item_cost'>
                    {{ $t("craftable-pro", "Current Item Cost") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='markup_percentage'>
                    {{ $t("craftable-pro", "Markup Percentage") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='price_before_tax'>
                    {{ $t("craftable-pro", "Price Before Tax") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='tax_value'>
                    {{ $t("craftable-pro", "Tax Value") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='price_after_tax'>
                    {{ $t("craftable-pro", "Price After Tax") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='price_before_discount'>
                    {{ $t("craftable-pro", "Price Before Discount") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='discount_value'>
                    {{ $t("craftable-pro", "Discount Value") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='price_after_discount'>
                    {{ $t("craftable-pro", "Price After Discount") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='price_adjustment'>
                    {{ $t("craftable-pro", "Price Adjustment") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='price_adjustment_reason'>
                    {{ $t("craftable-pro", "Price Adjustment Reason") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='price'>
                    {{ $t("craftable-pro", "Price") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='is_canceled'>
                    {{ $t("craftable-pro", "Is Canceled") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='canceled_time'>
                    {{ $t("craftable-pro", "Canceled Time") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='cancel_reason'>
                    {{ $t("craftable-pro", "Cancel Reason") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='return_required'>
                    {{ $t("craftable-pro", "Return Required") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='return_quantity'>
                    {{ $t("craftable-pro", "Return Quantity") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='return_time'>
                    {{ $t("craftable-pro", "Return Time") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='customer_review'>
                    {{ $t("craftable-pro", "Customer Review") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='customer_like'>
                    {{ $t("craftable-pro", "Customer Like") }}
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
                     {{ item.id }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.store_id }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.order_id }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.item_id }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.line_no }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.description }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.customer_notes }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.quantity }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.current_item_cost }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.markup_percentage }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.price_before_tax }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.tax_value }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.price_after_tax }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.price_before_discount }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.discount_value }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.price_after_discount }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.price_adjustment }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.price_adjustment_reason }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.price }}
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="is_canceled"
                        v-model="item.is_canceled"
                        :updateUrl="route('craftable-pro.order-lines.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.canceled_time && dayjs(item.canceled_time).format('DD.MM.YYYY HH:mm') }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.cancel_reason }}
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="return_required"
                        v-model="item.return_required"
                        :updateUrl="route('craftable-pro.order-lines.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.return_quantity }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.return_time && dayjs(item.return_time).format('DD.MM.YYYY HH:mm') }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.customer_review }}
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="customer_like"
                        v-model="item.customer_like"
                        :updateUrl="route('craftable-pro.order-lines.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.comments }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.created_at && dayjs(item.created_at).format('DD.MM.YYYY HH:mm') }}
                </ListingDataCell>
                <ListingDataCell>
                    <div class="flex items-center justify-end gap-3">
                        <IconButton
                            :as="Link"
                            :href="route('craftable-pro.order-lines.edit', item)"
                            variant="ghost"
                            color="gray"
                            :icon="PencilSquareIcon"
                            v-can="'craftable-pro.order-lines.edit'"
                        />

                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <IconButton
                                    @click="() => setIsOpen(true)"
                                    color="gray"
                                    variant="ghost"
                                    :icon="TrashIcon"
                                    v-can="'craftable-pro.order-lines.destroy'"
                                />
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
                                            action('delete', route('craftable-pro.order-lines.destroy', item), {
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
import type { OrderLine } from "./types";
import type { PageProps } from "craftable-pro/types/page";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat)



interface Props {
    orderLines: PaginatedCollection<OrderLine>;
}
defineProps<Props>();

</script>
