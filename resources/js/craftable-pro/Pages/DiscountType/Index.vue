<template>
    <PageHeader :title="$t('craftable-pro', 'Discount Types')">
        <Button
            :leftIcon="PlusIcon"
            :as="Link"
            :href="route('craftable-pro.discount-types.create')"
            v-can="'craftable-pro.discount-types.create'"
        >
            {{ $t("craftable-pro", "New Discount Type") }}
        </Button>
        
    </PageHeader>

    <PageContent>
        <Listing
            :baseUrl="route('craftable-pro.discount-types.index')"
            :data="discountTypes"
            dataKey="discountTypes"
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
                            v-can="'craftable-pro.discount-types.destroy'"
                        >
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                    </template>

                    <template #title>
                        {{ $t("craftable-pro", "Delete Discount Type") }}
                    </template>

                    <template #content>
                        {{
                            $t(
                                "craftable-pro",
                                "Are you sure you want to delete selected Discount Type? All data will be permanently removed from our servers forever. This action cannot be undone."
                            )
                        }}
                    </template>

                    <template #buttons="{ setIsOpen }">
                        <Button
                            @click.prevent="
                                () => {
                                    bulkAction('post', route('craftable-pro.discount-types.bulk-destroy'), {
                                        onFinish: () => setIsOpen(false),
                                    });
                                }
                            "
                            color="danger"
                            v-can="'craftable-pro.discount-types.destroy'"
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
                <ListingHeaderCell sortBy='name'>
                    {{ $t("craftable-pro", "Name") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='description'>
                    {{ $t("craftable-pro", "Description") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='is_percentage'>
                    {{ $t("craftable-pro", "Is Percentage") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='value'>
                    {{ $t("craftable-pro", "Value") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='coupon_code'>
                    {{ $t("craftable-pro", "Coupon Code") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='min_order_value'>
                    {{ $t("craftable-pro", "Min Order Value") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='min_item_quantity'>
                    {{ $t("craftable-pro", "Min Item Quantity") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='apply_to_all'>
                    {{ $t("craftable-pro", "Apply To All") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='apply_to_next'>
                    {{ $t("craftable-pro", "Apply To Next") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='max_discount_value'>
                    {{ $t("craftable-pro", "Max Discount Value") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='start_time'>
                    {{ $t("craftable-pro", "Start Time") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='end_time'>
                    {{ $t("craftable-pro", "End Time") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='is_active'>
                    {{ $t("craftable-pro", "Is Active") }}
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
                    <span class="font-medium text-gray-900 dark:text-white">{{ item.name }}</span>
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.description }}
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="is_percentage"
                        v-model="item.is_percentage"
                        :updateUrl="route('craftable-pro.discount-types.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.value }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.coupon_code }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.min_order_value }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.min_item_quantity }}
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="apply_to_all"
                        v-model="item.apply_to_all"
                        :updateUrl="route('craftable-pro.discount-types.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="apply_to_next"
                        v-model="item.apply_to_next"
                        :updateUrl="route('craftable-pro.discount-types.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.max_discount_value }}
                </ListingDataCell>
                <ListingDataCell>
                    <span class="text-sm text-gray-500">{{ item.start_time && dayjs(item.start_time).format('DD MMM YYYY') }}</span>
                </ListingDataCell>
                <ListingDataCell>
                    <span class="text-sm text-gray-500">{{ item.end_time && dayjs(item.end_time).format('DD MMM YYYY') }}</span>
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="is_active"
                        v-model="item.is_active"
                        :updateUrl="route('craftable-pro.discount-types.update', item.id)"
                    />
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
                            :href="route('craftable-pro.discount-types.edit', item)"
                            variant="ghost"
                            color="gray"
                            :icon="PencilSquareIcon"
                            v-can="'craftable-pro.discount-types.edit'"
                        />

                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <IconButton
                                    @click="() => setIsOpen(true)"
                                    color="gray"
                                    variant="ghost"
                                    :icon="TrashIcon"
                                    v-can="'craftable-pro.discount-types.destroy'"
                                />
                            </template>

                            <template #title>
                                {{ $t("craftable-pro", "Delete Discount Type") }}
                            </template>

                            <template #content>
                                {{
                                    $t(
                                        "craftable-pro",
                                        "Are you sure you want to delete selected Discount Type? All data will be permanently removed from our servers forever. This action cannot be undone."
                                    )
                                }}
                            </template>

                            <template #buttons="{ setIsOpen }">
                                <Button
                                    @click.prevent="
                                        () => {
                                            action('delete', route('craftable-pro.discount-types.destroy', item), {
                                                onFinish: () => setIsOpen(false),
                                            });
                                        }
                                    "
                                    color="danger"
                                    v-can="'craftable-pro.discount-types.destroy'"
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
import type { DiscountType } from "./types";
import type { PageProps } from "craftable-pro/types/page";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat)



interface Props {
    discountTypes: PaginatedCollection<DiscountType>;
}
defineProps<Props>();

</script>
