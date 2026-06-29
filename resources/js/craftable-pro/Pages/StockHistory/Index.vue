<template>
    <PageHeader :title="$t('craftable-pro', 'Stock Histories')">
        <Button
            :leftIcon="PlusIcon"
            :as="Link"
            :href="route('craftable-pro.stock-histories.create')"
            v-can="'craftable-pro.stock-histories.create'"
        >
            {{ $t("craftable-pro", "New Stock History") }}
        </Button>
        
    </PageHeader>

    <PageContent>
        <Listing
            :baseUrl="route('craftable-pro.stock-histories.index')"
            :data="stockHistories"
            dataKey="stockHistories"
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
                            v-can="'craftable-pro.stock-histories.destroy'"
                        >
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                    </template>

                    <template #title>
                        {{ $t("craftable-pro", "Delete Stock History") }}
                    </template>

                    <template #content>
                        {{
                            $t(
                                "craftable-pro",
                                "Are you sure you want to delete selected Stock History? All data will be permanently removed from our servers forever. This action cannot be undone."
                            )
                        }}
                    </template>

                    <template #buttons="{ setIsOpen }">
                        <Button
                            @click.prevent="
                                () => {
                                    bulkAction('post', route('craftable-pro.stock-histories.bulk-destroy'), {
                                        onFinish: () => setIsOpen(false),
                                    });
                                }
                            "
                            color="danger"
                            v-can="'craftable-pro.stock-histories.destroy'"
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
                <ListingHeaderCell sortBy='initial_stock_quantity'>
                    {{ $t("craftable-pro", "Initial Stock Quantity") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='initial_item_cost'>
                    {{ $t("craftable-pro", "Initial Item Cost") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='is_stock_entry'>
                    {{ $t("craftable-pro", "Is Stock Entry") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='quantity'>
                    {{ $t("craftable-pro", "Quantity") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='current_stock_quantity'>
                    {{ $t("craftable-pro", "Current Stock Quantity") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='current_item_cost'>
                    {{ $t("craftable-pro", "Current Item Cost") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='description'>
                    {{ $t("craftable-pro", "Description") }}
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
                    <span class="font-medium text-gray-900 dark:text-white">{{ item.initial_stock_quantity }}</span>
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.initial_item_cost }}
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="is_stock_entry"
                        v-model="item.is_stock_entry"
                        :updateUrl="route('craftable-pro.stock-histories.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.quantity }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.current_stock_quantity }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.current_item_cost }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.description }}
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
                            :href="route('craftable-pro.stock-histories.edit', item)"
                            variant="ghost"
                            color="gray"
                            :icon="PencilSquareIcon"
                            v-can="'craftable-pro.stock-histories.edit'"
                        />

                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <IconButton
                                    @click="() => setIsOpen(true)"
                                    color="gray"
                                    variant="ghost"
                                    :icon="TrashIcon"
                                    v-can="'craftable-pro.stock-histories.destroy'"
                                />
                            </template>

                            <template #title>
                                {{ $t("craftable-pro", "Delete Stock History") }}
                            </template>

                            <template #content>
                                {{
                                    $t(
                                        "craftable-pro",
                                        "Are you sure you want to delete selected Stock History? All data will be permanently removed from our servers forever. This action cannot be undone."
                                    )
                                }}
                            </template>

                            <template #buttons="{ setIsOpen }">
                                <Button
                                    @click.prevent="
                                        () => {
                                            action('delete', route('craftable-pro.stock-histories.destroy', item), {
                                                onFinish: () => setIsOpen(false),
                                            });
                                        }
                                    "
                                    color="danger"
                                    v-can="'craftable-pro.stock-histories.destroy'"
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
import type { StockHistory } from "./types";
import type { PageProps } from "craftable-pro/types/page";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat)



interface Props {
    stockHistories: PaginatedCollection<StockHistory>;
}
defineProps<Props>();

</script>
