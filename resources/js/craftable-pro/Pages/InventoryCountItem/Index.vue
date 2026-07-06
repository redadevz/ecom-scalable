<template>
    <PageHeader :title="$t('craftable-pro', 'Inventory Count Items')">
        <Button
            :leftIcon="PlusIcon"
            :as="Link"
            :href="route('craftable-pro.inventory-count-items.create')"
            v-can="'craftable-pro.inventory-count-items.create'"
        >
            {{ $t("craftable-pro", "New Inventory Count Item") }}
        </Button>
    </PageHeader>

    <PageContent>
        <Listing
            :baseUrl="route('craftable-pro.inventory-count-items.index')"
            :data="inventoryCountItems"
            dataKey="inventoryCountItems"
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
                            v-can="'craftable-pro.inventory-count-items.destroy'"
                        >
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                    </template>

                    <template #title>
                        {{ $t("craftable-pro", "Delete Inventory Count Item") }}
                    </template>

                    <template #content>
                        {{
                            $t(
                                "craftable-pro",
                                "Are you sure you want to delete selected Inventory Count Item? All data will be permanently removed from our servers forever. This action cannot be undone."
                            )
                        }}
                    </template>

                    <template #buttons="{ setIsOpen }">
                        <Button
                            @click.prevent="
                                () => {
                                    bulkAction('post', route('craftable-pro.inventory-count-items.bulk-destroy'), {
                                        onFinish: () => setIsOpen(false),
                                    });
                                }
                            "
                            color="danger"
                            v-can="'craftable-pro.inventory-count-items.destroy'"
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
                <ListingHeaderCell sortBy='inventory_count_id'>{{ $t("craftable-pro", "Inventory Count") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='quantity_expected'>{{ $t("craftable-pro", "Expected") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='quantity_counted'>{{ $t("craftable-pro", "Counted") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='quantity_change'>{{ $t("craftable-pro", "Change") }}</ListingHeaderCell>
                <ListingHeaderCell><span class="sr-only">{{ $t("craftable-pro", "Actions") }}</span></ListingHeaderCell>
            </template>

            <template #tableRow="{ item, action }: any">
                <!-- Item: initials avatar + name + description -->
                <ListingDataCell>
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-primary-500/10 text-sm font-bold uppercase text-primary-600 dark:text-primary-400">
                            {{ (item.item?.name || '?').slice(0, 2) }}
                        </span>
                        <div class="flex flex-col leading-tight">
                            <span class="font-medium text-gray-900 dark:text-white">{{ item.item?.name || '—' }}</span>
                            <span class="text-xs text-gray-400 max-w-[220px] truncate" :title="item.description">{{ item.description || '' }}</span>
                        </div>
                    </div>
                </ListingDataCell>

                <!-- Inventory Count reference -->
                <ListingDataCell>
                    <span class="inline-flex items-center rounded-lg bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600 dark:bg-white/5 dark:text-gray-300">
                        #{{ item.inventory_count_id }}
                    </span>
                </ListingDataCell>

                <!-- Expected -->
                <ListingDataCell>
                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ item.quantity_expected }}</span>
                </ListingDataCell>

                <!-- Counted -->
                <ListingDataCell>
                    <span class="font-medium text-gray-900 dark:text-white">{{ item.quantity_counted }}</span>
                </ListingDataCell>

                <!-- Change: signed pill -->
                <ListingDataCell>
                    <span
                        class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium"
                        :class="Number(item.quantity_change) > 0
                            ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400'
                            : Number(item.quantity_change) < 0
                                ? 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400'
                                : 'bg-gray-100 text-gray-500 dark:bg-white/5 dark:text-gray-400'"
                    >
                        <span class="h-1.5 w-1.5 rounded-full"
                            :class="Number(item.quantity_change) > 0 ? 'bg-green-500' : Number(item.quantity_change) < 0 ? 'bg-red-500' : 'bg-gray-400'"></span>
                        {{ Number(item.quantity_change) > 0 ? '+' : '' }}{{ item.quantity_change }}
                    </span>
                </ListingDataCell>

                <!-- Actions: rounded icon buttons (Larkon) -->
                <ListingDataCell>
                    <div class="flex items-center justify-center gap-2">
                        <Link :href="route('craftable-pro.inventory-count-items.edit', item)" title="View"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500 transition-colors hover:bg-gray-200 dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10">
                            <EyeIcon class="h-4 w-4" />
                        </Link>
                        <Link :href="route('craftable-pro.inventory-count-items.edit', item)" title="Edit" v-can="'craftable-pro.inventory-count-items.edit'"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary-600 transition-colors hover:bg-primary-100 dark:bg-primary-500/10 dark:text-primary-400 dark:hover:bg-primary-500/20">
                            <PencilSquareIcon class="h-4 w-4" />
                        </Link>
                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <button @click="() => setIsOpen(true)" title="Delete" v-can="'craftable-pro.inventory-count-items.destroy'"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 transition-colors hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20">
                                    <TrashIcon class="h-4 w-4" />
                                </button>
                            </template>
                            <template #title>{{ $t("craftable-pro", "Delete Inventory Count Item") }}</template>
                            <template #content>
                                {{ $t("craftable-pro", "Are you sure you want to delete selected Inventory Count Item? All data will be permanently removed from our servers forever. This action cannot be undone.") }}
                            </template>
                            <template #buttons="{ setIsOpen }">
                                <Button @click.prevent="() => { action('delete', route('craftable-pro.inventory-count-items.destroy', item), { onFinish: () => setIsOpen(false) }); }" color="danger" v-can="'craftable-pro.inventory-count-items.destroy'">
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
import type { InventoryCountItem } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat)

interface Props {
    inventoryCountItems: PaginatedCollection<InventoryCountItem>;
}
defineProps<Props>();
</script>
