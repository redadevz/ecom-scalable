<template>
    <PageHeader :title="$t('craftable-pro', 'Supplier Item Tax Types')">
        <Button
            :leftIcon="PlusIcon"
            :as="Link"
            :href="route('craftable-pro.supplier-item-tax-types.create')"
            v-can="'craftable-pro.supplier-item-tax-types.create'"
        >
            {{ $t("craftable-pro", "New Supplier Item Tax Type") }}
        </Button>
    </PageHeader>

    <PageContent>
        <Listing
            :baseUrl="route('craftable-pro.supplier-item-tax-types.index')"
            :data="supplierItemTaxTypes"
            dataKey="supplierItemTaxTypes"
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
                            v-can="'craftable-pro.supplier-item-tax-types.destroy'"
                        >
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                    </template>

                    <template #title>
                        {{ $t("craftable-pro", "Delete Supplier Item Tax Type") }}
                    </template>

                    <template #content>
                        {{
                            $t(
                                "craftable-pro",
                                "Are you sure you want to delete selected Supplier Item Tax Type? All data will be permanently removed from our servers forever. This action cannot be undone."
                            )
                        }}
                    </template>

                    <template #buttons="{ setIsOpen }">
                        <Button
                            @click.prevent="
                                () => {
                                    bulkAction('post', route('craftable-pro.supplier-item-tax-types.bulk-destroy'), {
                                        onFinish: () => setIsOpen(false),
                                    });
                                }
                            "
                            color="danger"
                            v-can="'craftable-pro.supplier-item-tax-types.destroy'"
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
                <ListingHeaderCell sortBy='supplier_tax_type_id'>{{ $t("craftable-pro", "Tax Type") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='start_time'>{{ $t("craftable-pro", "Period") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='description'>{{ $t("craftable-pro", "Description") }}</ListingHeaderCell>
                <ListingHeaderCell>{{ $t("craftable-pro", "Status") }}</ListingHeaderCell>
                <ListingHeaderCell><span class="sr-only">{{ $t("craftable-pro", "Actions") }}</span></ListingHeaderCell>
            </template>

            <template #tableRow="{ item, action }: any">
                <!-- Item: initials avatar + name + id -->
                <ListingDataCell>
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-primary-500/10 text-sm font-bold uppercase text-primary-600 dark:text-primary-400">
                            {{ (item.item?.name || '?').slice(0, 2) }}
                        </span>
                        <div class="flex flex-col">
                            <span class="font-medium text-gray-900 dark:text-white">{{ item.item?.name || '—' }}</span>
                            <span class="text-xs text-gray-400">#{{ item.id }}</span>
                        </div>
                    </div>
                </ListingDataCell>

                <!-- Tax type + supplier -->
                <ListingDataCell>
                    <div class="flex flex-col leading-tight">
                        <span class="text-sm text-gray-900 dark:text-white">{{ item.supplier_tax_type?.name || '—' }}</span>
                        <span class="text-xs text-gray-400">{{ supplierName(item.supplier_tax_type?.supplier) }}</span>
                    </div>
                </ListingDataCell>

                <!-- Period: start -> end -->
                <ListingDataCell>
                    <div class="flex flex-col leading-tight">
                        <span class="text-sm text-gray-700 dark:text-gray-200">{{ item.start_time ? dayjs(item.start_time).format('DD MMM YYYY') : '—' }}</span>
                        <span class="text-xs text-gray-400">{{ item.end_time ? '→ ' + dayjs(item.end_time).format('DD MMM YYYY') : 'No end date' }}</span>
                    </div>
                </ListingDataCell>

                <!-- Description -->
                <ListingDataCell>
                    <span class="block max-w-[220px] truncate text-sm text-gray-600 dark:text-gray-300" :title="item.description">{{ item.description || '—' }}</span>
                </ListingDataCell>

                <!-- Status pill (derived from period) -->
                <ListingDataCell>
                    <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium" :class="periodStatus(item).cls">
                        <span class="h-1.5 w-1.5 rounded-full" :class="periodStatus(item).dot"></span>
                        {{ periodStatus(item).label }}
                    </span>
                </ListingDataCell>

                <!-- Actions -->
                <ListingDataCell>
                    <div class="flex items-center justify-center gap-2">
                        <Link :href="route('craftable-pro.supplier-item-tax-types.edit', item)" title="View"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500 transition-colors hover:bg-gray-200 dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10">
                            <EyeIcon class="h-4 w-4" />
                        </Link>
                        <Link :href="route('craftable-pro.supplier-item-tax-types.edit', item)" title="Edit" v-can="'craftable-pro.supplier-item-tax-types.edit'"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary-600 transition-colors hover:bg-primary-100 dark:bg-primary-500/10 dark:text-primary-400 dark:hover:bg-primary-500/20">
                            <PencilSquareIcon class="h-4 w-4" />
                        </Link>
                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <button @click="() => setIsOpen(true)" title="Delete" v-can="'craftable-pro.supplier-item-tax-types.destroy'"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 transition-colors hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20">
                                    <TrashIcon class="h-4 w-4" />
                                </button>
                            </template>
                            <template #title>{{ $t("craftable-pro", "Delete Supplier Item Tax Type") }}</template>
                            <template #content>
                                {{ $t("craftable-pro", "Are you sure you want to delete selected Supplier Item Tax Type? All data will be permanently removed from our servers forever. This action cannot be undone.") }}
                            </template>
                            <template #buttons="{ setIsOpen }">
                                <Button @click.prevent="() => { action('delete', route('craftable-pro.supplier-item-tax-types.destroy', item), { onFinish: () => setIsOpen(false) }); }" color="danger" v-can="'craftable-pro.supplier-item-tax-types.destroy'">
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
import type { SupplierItemTaxType } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat);

interface Props {
    supplierItemTaxTypes: PaginatedCollection<SupplierItemTaxType>;
}
defineProps<Props>();

const supplierName = (supplier: any): string => {
    if (!supplier) return "";
    if (supplier.company_name) return supplier.company_name;
    const full = [supplier.first_name, supplier.last_name].filter(Boolean).join(" ").trim();
    return full;
};

const periodStatus = (item: any): { label: string; cls: string; dot: string } => {
    const now = dayjs();
    const start = item.start_time ? dayjs(item.start_time) : null;
    const end = item.end_time ? dayjs(item.end_time) : null;
    if (start && now.isBefore(start)) {
        return { label: "Scheduled", cls: "bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400", dot: "bg-amber-500" };
    }
    if (end && now.isAfter(end)) {
        return { label: "Expired", cls: "bg-gray-100 text-gray-500 dark:bg-white/5 dark:text-gray-400", dot: "bg-gray-400" };
    }
    return { label: "Active", cls: "bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400", dot: "bg-green-500" };
};
</script>
