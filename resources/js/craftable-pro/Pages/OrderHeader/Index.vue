<template>
    <PageHeader :title="$t('craftable-pro', 'Order Headers')">
        <Button
            :leftIcon="PlusIcon"
            :as="Link"
            :href="route('craftable-pro.order-headers.create')"
            v-can="'craftable-pro.order-headers.create'"
        >
            {{ $t("craftable-pro", "New Order Header") }}
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
                        <Button
                            @click="() => setIsOpen(true)"
                            color="gray"
                            variant="outline"
                            size="sm"
                            :leftIcon="TrashIcon"
                            v-can="'craftable-pro.order-headers.destroy'"
                        >
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                    </template>

                    <template #title>
                        {{ $t("craftable-pro", "Delete Order Header") }}
                    </template>

                    <template #content>
                        {{
                            $t(
                                "craftable-pro",
                                "Are you sure you want to delete selected Order Header? All data will be permanently removed from our servers forever. This action cannot be undone."
                            )
                        }}
                    </template>

                    <template #buttons="{ setIsOpen }">
                        <Button
                            @click.prevent="
                                () => {
                                    bulkAction('post', route('craftable-pro.order-headers.bulk-destroy'), {
                                        onFinish: () => setIsOpen(false),
                                    });
                                }
                            "
                            color="danger"
                            v-can="'craftable-pro.order-headers.destroy'"
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
                <ListingHeaderCell sortBy='sale_channel_id'>
                    {{ $t("craftable-pro", "Sale Channel Id") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='delivery_type_id'>
                    {{ $t("craftable-pro", "Delivery Type Id") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='payment_method_id'>
                    {{ $t("craftable-pro", "Payment Method Id") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='payment_time_id'>
                    {{ $t("craftable-pro", "Payment Time Id") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='customer_id'>
                    {{ $t("craftable-pro", "Customer Id") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='loyalty_card_id'>
                    {{ $t("craftable-pro", "Loyalty Card Id") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='created_by'>
                    {{ $t("craftable-pro", "Created By") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='approved_by'>
                    {{ $t("craftable-pro", "Approved By") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='managed_by'>
                    {{ $t("craftable-pro", "Managed By") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='order_no'>
                    {{ $t("craftable-pro", "Order No") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='customer_notes'>
                    {{ $t("craftable-pro", "Customer Notes") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='price_before_tax'>
                    {{ $t("craftable-pro", "Price Before Tax") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='total_tax_value'>
                    {{ $t("craftable-pro", "Total Tax Value") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='price_after_tax'>
                    {{ $t("craftable-pro", "Price After Tax") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='price_before_discount'>
                    {{ $t("craftable-pro", "Price Before Discount") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='order_items_discount'>
                    {{ $t("craftable-pro", "Order Items Discount") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='order_discount'>
                    {{ $t("craftable-pro", "Order Discount") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='total_discount_value'>
                    {{ $t("craftable-pro", "Total Discount Value") }}
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
                <ListingHeaderCell sortBy='latest_status'>
                    {{ $t("craftable-pro", "Latest Status") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='latest_status_update'>
                    {{ $t("craftable-pro", "Latest Status Update") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='is_submitted'>
                    {{ $t("craftable-pro", "Is Submitted") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='submitted_time'>
                    {{ $t("craftable-pro", "Submitted Time") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='is_approved'>
                    {{ $t("craftable-pro", "Is Approved") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='approved_time'>
                    {{ $t("craftable-pro", "Approved Time") }}
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
                <ListingHeaderCell sortBy='is_scheduled'>
                    {{ $t("craftable-pro", "Is Scheduled") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='scheduled_time'>
                    {{ $t("craftable-pro", "Scheduled Time") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='is_ready'>
                    {{ $t("craftable-pro", "Is Ready") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='ready_time'>
                    {{ $t("craftable-pro", "Ready Time") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='is_delivered'>
                    {{ $t("craftable-pro", "Is Delivered") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='delivered_time'>
                    {{ $t("craftable-pro", "Delivered Time") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='is_paid'>
                    {{ $t("craftable-pro", "Is Paid") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='payment_time'>
                    {{ $t("craftable-pro", "Payment Time") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='is_completed'>
                    {{ $t("craftable-pro", "Is Completed") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='completed_time'>
                    {{ $t("craftable-pro", "Completed Time") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='return_required'>
                    {{ $t("craftable-pro", "Return Required") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='return_time'>
                    {{ $t("craftable-pro", "Return Time") }}
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
                     {{ item.sale_channel_id }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.delivery_type_id }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.payment_method_id }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.payment_time_id }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.customer_id }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.loyalty_card_id }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.created_by }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.approved_by }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.managed_by }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.order_no }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.customer_notes }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.price_before_tax }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.total_tax_value }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.price_after_tax }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.price_before_discount }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.order_items_discount }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.order_discount }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.total_discount_value }}
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
                     {{ item.latest_status }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.latest_status_update && dayjs(item.latest_status_update).format('DD.MM.YYYY HH:mm') }}
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="is_submitted"
                        v-model="item.is_submitted"
                        :updateUrl="route('craftable-pro.order-headers.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.submitted_time && dayjs(item.submitted_time).format('DD.MM.YYYY HH:mm') }}
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="is_approved"
                        v-model="item.is_approved"
                        :updateUrl="route('craftable-pro.order-headers.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.approved_time && dayjs(item.approved_time).format('DD.MM.YYYY HH:mm') }}
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="is_canceled"
                        v-model="item.is_canceled"
                        :updateUrl="route('craftable-pro.order-headers.update', item.id)"
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
                        name="is_scheduled"
                        v-model="item.is_scheduled"
                        :updateUrl="route('craftable-pro.order-headers.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.scheduled_time && dayjs(item.scheduled_time).format('DD.MM.YYYY HH:mm') }}
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="is_ready"
                        v-model="item.is_ready"
                        :updateUrl="route('craftable-pro.order-headers.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.ready_time && dayjs(item.ready_time).format('DD.MM.YYYY HH:mm') }}
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="is_delivered"
                        v-model="item.is_delivered"
                        :updateUrl="route('craftable-pro.order-headers.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.delivered_time && dayjs(item.delivered_time).format('DD.MM.YYYY HH:mm') }}
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="is_paid"
                        v-model="item.is_paid"
                        :updateUrl="route('craftable-pro.order-headers.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.payment_time && dayjs(item.payment_time).format('DD.MM.YYYY HH:mm') }}
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="is_completed"
                        v-model="item.is_completed"
                        :updateUrl="route('craftable-pro.order-headers.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.completed_time && dayjs(item.completed_time).format('DD.MM.YYYY HH:mm') }}
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="return_required"
                        v-model="item.return_required"
                        :updateUrl="route('craftable-pro.order-headers.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.return_time && dayjs(item.return_time).format('DD.MM.YYYY HH:mm') }}
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
                            :href="route('craftable-pro.order-headers.edit', item)"
                            variant="ghost"
                            color="gray"
                            :icon="PencilSquareIcon"
                            v-can="'craftable-pro.order-headers.edit'"
                        />

                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <IconButton
                                    @click="() => setIsOpen(true)"
                                    color="gray"
                                    variant="ghost"
                                    :icon="TrashIcon"
                                    v-can="'craftable-pro.order-headers.destroy'"
                                />
                            </template>

                            <template #title>
                                {{ $t("craftable-pro", "Delete Order Header") }}
                            </template>

                            <template #content>
                                {{
                                    $t(
                                        "craftable-pro",
                                        "Are you sure you want to delete selected Order Header? All data will be permanently removed from our servers forever. This action cannot be undone."
                                    )
                                }}
                            </template>

                            <template #buttons="{ setIsOpen }">
                                <Button
                                    @click.prevent="
                                        () => {
                                            action('delete', route('craftable-pro.order-headers.destroy', item), {
                                                onFinish: () => setIsOpen(false),
                                            });
                                        }
                                    "
                                    color="danger"
                                    v-can="'craftable-pro.order-headers.destroy'"
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
import type { OrderHeader } from "./types";
import type { PageProps } from "craftable-pro/types/page";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat)



interface Props {
    orderHeaders: PaginatedCollection<OrderHeader>;
}
defineProps<Props>();

</script>
