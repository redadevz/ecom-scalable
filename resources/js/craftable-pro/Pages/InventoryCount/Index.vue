<template>
    <PageHeader :title="$t('craftable-pro', 'Inventory Counts')">
        <Button
            :leftIcon="PlusIcon"
            :as="Link"
            :href="route('craftable-pro.inventory-counts.create')"
            v-can="'craftable-pro.inventory-counts.create'"
        >
            {{ $t("craftable-pro", "New Inventory Count") }}
        </Button>
    </PageHeader>

    <PageContent>
        <Listing
            :baseUrl="route('craftable-pro.inventory-counts.index')"
            :data="inventoryCounts"
            dataKey="inventoryCounts"
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
                            v-can="'craftable-pro.inventory-counts.destroy'"
                        >
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                    </template>

                    <template #title>
                        {{ $t("craftable-pro", "Delete Inventory Count") }}
                    </template>

                    <template #content>
                        {{
                            $t(
                                "craftable-pro",
                                "Are you sure you want to delete selected Inventory Count? All data will be permanently removed from our servers forever. This action cannot be undone."
                            )
                        }}
                    </template>

                    <template #buttons="{ setIsOpen }">
                        <Button
                            @click.prevent="
                                () => {
                                    bulkAction('post', route('craftable-pro.inventory-counts.bulk-destroy'), {
                                        onFinish: () => setIsOpen(false),
                                    });
                                }
                            "
                            color="danger"
                            v-can="'craftable-pro.inventory-counts.destroy'"
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
                <ListingHeaderCell sortBy='id'>{{ $t("craftable-pro", "Count") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='store_id'>{{ $t("craftable-pro", "Store") }}</ListingHeaderCell>
                <ListingHeaderCell>{{ $t("craftable-pro", "Items") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='change_stock_time'>{{ $t("craftable-pro", "Status") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='created_at'>{{ $t("craftable-pro", "Date") }}</ListingHeaderCell>
                <ListingHeaderCell><span class="sr-only">{{ $t("craftable-pro", "Actions") }}</span></ListingHeaderCell>
            </template>

            <template #tableRow="{ item, action }: any">
                <!-- Count: initials avatar + number + description -->
                <ListingDataCell>
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-primary-500/10 text-sm font-bold uppercase text-primary-600 dark:text-primary-400">
                            #{{ item.id }}
                        </span>
                        <div class="flex flex-col leading-tight">
                            <span class="font-medium text-gray-900 dark:text-white">{{ item.description || $t("craftable-pro", "Count") + ' #' + item.id }}</span>
                            <span class="max-w-[220px] truncate text-xs text-gray-400" :title="item.comments">{{ item.comments || '—' }}</span>
                        </div>
                    </div>
                </ListingDataCell>

                <!-- Store -->
                <ListingDataCell>
                    <span class="text-sm text-gray-700 dark:text-gray-200">{{ item.store?.name || '—' }}</span>
                </ListingDataCell>

                <!-- Items count -->
                <ListingDataCell>
                    <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-white/5 dark:text-gray-300">
                        {{ item.inventory_count_items_count ?? 0 }} {{ $t("craftable-pro", "items") }}
                    </span>
                </ListingDataCell>

                <!-- Status: Applied (green) vs Pending (amber) -->
                <ListingDataCell>
                    <span
                        v-if="item.change_stock_time"
                        class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 dark:bg-green-500/10 dark:text-green-400"
                    >
                        {{ $t("craftable-pro", "Applied") }}
                    </span>
                    <span
                        v-else
                        class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-700 dark:bg-amber-500/10 dark:text-amber-400"
                    >
                        {{ $t("craftable-pro", "Pending") }}
                    </span>
                </ListingDataCell>

                <!-- Date -->
                <ListingDataCell>
                    <span class="text-sm text-gray-500">{{ item.created_at && dayjs(item.created_at).format('DD MMM YYYY') }}</span>
                </ListingDataCell>

                <!-- Actions: rounded icon buttons (Larkon) -->
                <ListingDataCell>
                    <div class="flex items-center justify-center gap-2">
                        <Link :href="route('craftable-pro.inventory-counts.edit', item)" title="View"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500 transition-colors hover:bg-gray-200 dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10">
                            <EyeIcon class="h-4 w-4" />
                        </Link>
                        <Link :href="route('craftable-pro.inventory-counts.edit', item)" title="Edit" v-can="'craftable-pro.inventory-counts.edit'"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary-600 transition-colors hover:bg-primary-100 dark:bg-primary-500/10 dark:text-primary-400 dark:hover:bg-primary-500/20">
                            <PencilSquareIcon class="h-4 w-4" />
                        </Link>
                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <button @click="() => setIsOpen(true)" title="Delete" v-can="'craftable-pro.inventory-counts.destroy'"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 transition-colors hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20">
                                    <TrashIcon class="h-4 w-4" />
                                </button>
                            </template>
                            <template #title>{{ $t("craftable-pro", "Delete Inventory Count") }}</template>
                            <template #content>
                                {{ $t("craftable-pro", "Are you sure you want to delete selected Inventory Count? All data will be permanently removed from our servers forever. This action cannot be undone.") }}
                            </template>
                            <template #buttons="{ setIsOpen }">
                                <Button @click.prevent="() => { action('delete', route('craftable-pro.inventory-counts.destroy', item), { onFinish: () => setIsOpen(false) }); }" color="danger" v-can="'craftable-pro.inventory-counts.destroy'">
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
import type { InventoryCount } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat)

interface Props {
    inventoryCounts: PaginatedCollection<InventoryCount>;
}
defineProps<Props>();
</script>
