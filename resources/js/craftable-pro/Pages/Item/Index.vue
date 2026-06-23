<template>
    <PageHeader :title="$t('craftable-pro', 'Items')">
        <Button
            :leftIcon="PlusIcon"
            :as="Link"
            :href="route('craftable-pro.items.create')"
            v-can="'craftable-pro.items.create'"
        >
            {{ $t("craftable-pro", "New Item") }}
        </Button>
        
    </PageHeader>

    <PageContent>
        <Listing
            :baseUrl="route('craftable-pro.items.index')"
            :data="items"
            dataKey="items"
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
                            v-can="'craftable-pro.items.destroy'"
                        >
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                    </template>

                    <template #title>
                        {{ $t("craftable-pro", "Delete Item") }}
                    </template>

                    <template #content>
                        {{
                            $t(
                                "craftable-pro",
                                "Are you sure you want to delete selected Item? All data will be permanently removed from our servers forever. This action cannot be undone."
                            )
                        }}
                    </template>

                    <template #buttons="{ setIsOpen }">
                        <Button
                            @click.prevent="
                                () => {
                                    bulkAction('post', route('craftable-pro.items.bulk-destroy'), {
                                        onFinish: () => setIsOpen(false),
                                    });
                                }
                            "
                            color="danger"
                            v-can="'craftable-pro.items.destroy'"
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
                <ListingHeaderCell sortBy='item_category_id'>
                    {{ $t("craftable-pro", "Item Category Id") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='supplier_id'>
                    {{ $t("craftable-pro", "Supplier Id") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='measure_unit_id'>
                    {{ $t("craftable-pro", "Measure Unit Id") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='sku_code'>
                    {{ $t("craftable-pro", "Sku Code") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='name'>
                    {{ $t("craftable-pro", "Name") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='description'>
                    {{ $t("craftable-pro", "Description") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='is_service'>
                    {{ $t("craftable-pro", "Is Service") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='in_stock'>
                    {{ $t("craftable-pro", "In Stock") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='using_default_quantity'>
                    {{ $t("craftable-pro", "Using Default Quantity") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='default_quantity'>
                    {{ $t("craftable-pro", "Default Quantity") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='current_stock_quantity'>
                    {{ $t("craftable-pro", "Current Stock Quantity") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='preferred_stock_quantity'>
                    {{ $t("craftable-pro", "Preferred Stock Quantity") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='min_stock_quantity'>
                    {{ $t("craftable-pro", "Min Stock Quantity") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='low_stock_warning'>
                    {{ $t("craftable-pro", "Low Stock Warning") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='low_stock_quantity'>
                    {{ $t("craftable-pro", "Low Stock Quantity") }}
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
                     {{ item.id }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.store_id }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.item_category_id }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.supplier_id }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.measure_unit_id }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.sku_code }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.name }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.description }}
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="is_service"
                        v-model="item.is_service"
                        :updateUrl="route('craftable-pro.items.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="in_stock"
                        v-model="item.in_stock"
                        :updateUrl="route('craftable-pro.items.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="using_default_quantity"
                        v-model="item.using_default_quantity"
                        :updateUrl="route('craftable-pro.items.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.default_quantity }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.current_stock_quantity }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.preferred_stock_quantity }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.min_stock_quantity }}
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="low_stock_warning"
                        v-model="item.low_stock_warning"
                        :updateUrl="route('craftable-pro.items.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.low_stock_quantity }}
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="is_active"
                        v-model="item.is_active"
                        :updateUrl="route('craftable-pro.items.update', item.id)"
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
                            :href="route('craftable-pro.items.edit', item)"
                            variant="ghost"
                            color="gray"
                            :icon="PencilSquareIcon"
                            v-can="'craftable-pro.items.edit'"
                        />

                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <IconButton
                                    @click="() => setIsOpen(true)"
                                    color="gray"
                                    variant="ghost"
                                    :icon="TrashIcon"
                                    v-can="'craftable-pro.items.destroy'"
                                />
                            </template>

                            <template #title>
                                {{ $t("craftable-pro", "Delete Item") }}
                            </template>

                            <template #content>
                                {{
                                    $t(
                                        "craftable-pro",
                                        "Are you sure you want to delete selected Item? All data will be permanently removed from our servers forever. This action cannot be undone."
                                    )
                                }}
                            </template>

                            <template #buttons="{ setIsOpen }">
                                <Button
                                    @click.prevent="
                                        () => {
                                            action('delete', route('craftable-pro.items.destroy', item), {
                                                onFinish: () => setIsOpen(false),
                                            });
                                        }
                                    "
                                    color="danger"
                                    v-can="'craftable-pro.items.destroy'"
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
import type { Item } from "./types";
import type { PageProps } from "craftable-pro/types/page";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat)



interface Props {
    items: PaginatedCollection<Item>;
}
defineProps<Props>();

</script>
