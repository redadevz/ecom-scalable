<template>
    <PageHeader :title="$t('craftable-pro', 'Purchase Items')">
        <Button
            :leftIcon="PlusIcon"
            :as="Link"
            :href="route('craftable-pro.purchase-items.create')"
            v-can="'craftable-pro.purchase-items.create'"
        >
            {{ $t("craftable-pro", "New Purchase Item") }}
        </Button>
    </PageHeader>

    <PageContent>
        <Listing
            :baseUrl="route('craftable-pro.purchase-items.index')"
            :data="purchaseItems"
            dataKey="purchaseItems"
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
                            v-can="'craftable-pro.purchase-items.destroy'"
                        >
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                    </template>

                    <template #title>
                        {{ $t("craftable-pro", "Delete Purchase Item") }}
                    </template>

                    <template #content>
                        {{
                            $t(
                                "craftable-pro",
                                "Are you sure you want to delete selected Purchase Item? All data will be permanently removed from our servers forever. This action cannot be undone."
                            )
                        }}
                    </template>

                    <template #buttons="{ setIsOpen }">
                        <Button
                            @click.prevent="
                                () => {
                                    bulkAction('post', route('craftable-pro.purchase-items.bulk-destroy'), {
                                        onFinish: () => setIsOpen(false),
                                    });
                                }
                            "
                            color="danger"
                            v-can="'craftable-pro.purchase-items.destroy'"
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
                <ListingHeaderCell sortBy='purchase_id'>{{ $t("craftable-pro", "Purchase") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='quantity'>{{ $t("craftable-pro", "Qty") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='supplier_price_after_tax'>{{ $t("craftable-pro", "Unit Cost") }}</ListingHeaderCell>
                <ListingHeaderCell>{{ $t("craftable-pro", "Line Total") }}</ListingHeaderCell>
                <ListingHeaderCell><span class="sr-only">{{ $t("craftable-pro", "Actions") }}</span></ListingHeaderCell>
            </template>

            <template #tableRow="{ item, action }: any">
                <!-- Item: initials avatar + name + description -->
                <ListingDataCell>
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-primary-500/10 text-sm font-bold uppercase text-primary-600 dark:text-primary-400">
                            {{ (item.item?.name || '?').slice(0, 2) }}
                        </span>
                        <div class="flex flex-col">
                            <span class="font-medium text-gray-900 dark:text-white">{{ item.item?.name || '—' }}</span>
                            <span class="block max-w-[220px] truncate text-xs text-gray-400" :title="item.description">{{ item.description || '' }}</span>
                        </div>
                    </div>
                </ListingDataCell>

                <!-- Purchase reference -->
                <ListingDataCell>
                    <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-white/5 dark:text-gray-300">#{{ item.purchase_id }}</span>
                </ListingDataCell>

                <!-- Quantity -->
                <ListingDataCell>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ item.quantity }}</span>
                </ListingDataCell>

                <!-- Unit cost -->
                <ListingDataCell>
                    <span class="text-sm text-gray-700 dark:text-gray-200">{{ money(item.supplier_price_after_tax) }}</span>
                </ListingDataCell>

                <!-- Line total -->
                <ListingDataCell>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ money(Number(item.supplier_price_after_tax ?? 0) * Number(item.quantity ?? 0)) }}</span>
                </ListingDataCell>

                <!-- Actions: rounded icon buttons (Larkon) -->
                <ListingDataCell>
                    <div class="flex items-center justify-center gap-2">
                        <Link :href="route('craftable-pro.purchase-items.edit', item)" title="View"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500 transition-colors hover:bg-gray-200 dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10">
                            <EyeIcon class="h-4 w-4" />
                        </Link>
                        <Link :href="route('craftable-pro.purchase-items.edit', item)" title="Edit" v-can="'craftable-pro.purchase-items.edit'"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary-600 transition-colors hover:bg-primary-100 dark:bg-primary-500/10 dark:text-primary-400 dark:hover:bg-primary-500/20">
                            <PencilSquareIcon class="h-4 w-4" />
                        </Link>
                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <button @click="() => setIsOpen(true)" title="Delete" v-can="'craftable-pro.purchase-items.destroy'"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 transition-colors hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20">
                                    <TrashIcon class="h-4 w-4" />
                                </button>
                            </template>
                            <template #title>{{ $t("craftable-pro", "Delete Purchase Item") }}</template>
                            <template #content>
                                {{ $t("craftable-pro", "Are you sure you want to delete selected Purchase Item? All data will be permanently removed from our servers forever. This action cannot be undone.") }}
                            </template>
                            <template #buttons="{ setIsOpen }">
                                <Button @click.prevent="() => { action('delete', route('craftable-pro.purchase-items.destroy', item), { onFinish: () => setIsOpen(false) }); }" color="danger" v-can="'craftable-pro.purchase-items.destroy'">
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
import type { PurchaseItem } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat)

const money = (v: any) =>
    Number(v ?? 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + " DH";

interface Props {
    purchaseItems: PaginatedCollection<PurchaseItem>;
}
defineProps<Props>();
</script>
