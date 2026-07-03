<template>
    <PageHeader :title="$t('craftable-pro', 'Items')">
        <div class="flex items-center gap-2">
            <div class="flex rounded-lg border border-gray-200 p-0.5 dark:border-[#2c2f3d]">
                <span class="flex h-8 w-8 items-center justify-center rounded-md bg-primary-500 text-white">
                    <ListBulletIcon class="h-5 w-5" />
                </span>
                <Link :href="route('craftable-pro.items.grid')" class="flex h-8 w-8 items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-white/5">
                    <Squares2X2Icon class="h-5 w-5" />
                </Link>
            </div>
            <Button
                :leftIcon="PlusIcon"
                :as="Link"
                :href="route('craftable-pro.items.create')"
                v-can="'craftable-pro.items.create'"
            >
                {{ $t("craftable-pro", "New Item") }}
            </Button>
        </div>
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
                <ListingHeaderCell sortBy='name'>Product</ListingHeaderCell>
                <ListingHeaderCell>Price</ListingHeaderCell>
                <ListingHeaderCell sortBy='current_stock_quantity'>Stock</ListingHeaderCell>
                <ListingHeaderCell>Category</ListingHeaderCell>
                <ListingHeaderCell sortBy='is_active'>Status</ListingHeaderCell>
                <ListingHeaderCell><span class="sr-only">Action</span></ListingHeaderCell>
            </template>

            <template #tableRow="{ item, action }: any">
                <!-- Product: thumbnail + name + SKU -->
                <ListingDataCell>
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-lg bg-gray-100 text-sm font-bold uppercase text-gray-500 dark:bg-white/5 dark:text-gray-300">
                            {{ (item.name || '?').slice(0, 2) }}
                        </span>
                        <div class="flex flex-col">
                            <span class="font-medium text-gray-900 dark:text-white">{{ item.name }}</span>
                            <span class="text-xs text-gray-400">{{ item.sku_code }}</span>
                        </div>
                    </div>
                </ListingDataCell>

                <!-- Price (active sale price) -->
                <ListingDataCell>
                    <span class="font-medium tabular-nums text-gray-900 dark:text-white">{{ price(item) }}</span>
                </ListingDataCell>

                <!-- Stock: qty + alert line -->
                <ListingDataCell>
                    <div class="flex flex-col leading-tight">
                        <span class="text-sm font-medium tabular-nums" :class="isLow(item) ? 'text-red-500' : 'text-gray-900 dark:text-white'">
                            {{ item.current_stock_quantity ?? 0 }} <span class="text-xs font-normal text-gray-400">in stock</span>
                        </span>
                        <span class="text-xs" :class="isLow(item) ? 'text-red-400' : 'text-gray-400'">
                            {{ isLow(item) ? 'Low stock' : 'Alert at ' + (item.low_stock_quantity ?? 0) }}
                        </span>
                    </div>
                </ListingDataCell>

                <!-- Category -->
                <ListingDataCell>
                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ item.item_category?.name ?? '—' }}</span>
                </ListingDataCell>

                <!-- Status -->
                <ListingDataCell>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                        :class="item.is_active ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400'">
                        {{ item.is_active ? 'Active' : 'Inactive' }}
                    </span>
                </ListingDataCell>

                <!-- Actions: rounded icon buttons (Larkon) -->
                <ListingDataCell>
                    <div class="flex items-center justify-center gap-2">
                        <Link :href="route('craftable-pro.items.edit', item)" title="View"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500 transition-colors hover:bg-gray-200 dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10">
                            <EyeIcon class="h-4 w-4" />
                        </Link>
                        <Link :href="route('craftable-pro.items.edit', item)" title="Edit" v-can="'craftable-pro.items.edit'"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary-600 transition-colors hover:bg-primary-100 dark:bg-primary-500/10 dark:text-primary-400 dark:hover:bg-primary-500/20">
                            <PencilSquareIcon class="h-4 w-4" />
                        </Link>
                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <button @click="() => setIsOpen(true)" title="Delete" v-can="'craftable-pro.items.destroy'"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 transition-colors hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20">
                                    <TrashIcon class="h-4 w-4" />
                                </button>
                            </template>
                            <template #title>{{ $t("craftable-pro", "Delete Item") }}</template>
                            <template #content>
                                {{ $t("craftable-pro", "Are you sure you want to delete selected Item? All data will be permanently removed from our servers forever. This action cannot be undone.") }}
                            </template>
                            <template #buttons="{ setIsOpen }">
                                <Button @click.prevent="() => { action('delete', route('craftable-pro.items.destroy', item), { onFinish: () => setIsOpen(false) }); }" color="danger" v-can="'craftable-pro.items.destroy'">
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
import { Link, usePage } from "@inertiajs/vue3";
import {
    PlusIcon,
    TrashIcon,
    PencilSquareIcon,
    ArrowDownTrayIcon,
    EyeIcon,
    ListBulletIcon,
    Squares2X2Icon,
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



const money = (v: number) =>
    Number(v ?? 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + " DH";

const price = (item: any) => {
    const p = item.prices?.[0]?.sale_price;
    return p != null ? money(p) : "—";
};

const isLow = (item: any) =>
    Number(item.current_stock_quantity ?? 0) <= Number(item.low_stock_quantity ?? 0);

interface Props {
    items: PaginatedCollection<Item>;
}
defineProps<Props>();

</script>
