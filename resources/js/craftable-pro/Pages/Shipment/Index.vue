<template>
    <PageHeader :title="$t('craftable-pro', 'Shipments')">
        <Button
            :leftIcon="PlusIcon"
            :as="Link"
            :href="route('craftable-pro.shipments.create')"
            v-can="'craftable-pro.shipments.create'"
        >
            {{ $t("craftable-pro", "New Shipment") }}
        </Button>
    </PageHeader>

    <PageContent>
        <Listing
            :baseUrl="route('craftable-pro.shipments.index')"
            :data="shipments"
            dataKey="shipments"
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
                            v-can="'craftable-pro.shipments.destroy'"
                        >
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                    </template>

                    <template #title>
                        {{ $t("craftable-pro", "Delete Shipment") }}
                    </template>

                    <template #content>
                        {{
                            $t(
                                "craftable-pro",
                                "Are you sure you want to delete selected Shipment? All data will be permanently removed from our servers forever. This action cannot be undone."
                            )
                        }}
                    </template>

                    <template #buttons="{ setIsOpen }">
                        <Button
                            @click.prevent="
                                () => {
                                    bulkAction('post', route('craftable-pro.shipments.bulk-destroy'), {
                                        onFinish: () => setIsOpen(false),
                                    });
                                }
                            "
                            color="danger"
                            v-can="'craftable-pro.shipments.destroy'"
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
                <ListingHeaderCell sortBy='id'>{{ $t("craftable-pro", "Shipment") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='order_id'>{{ $t("craftable-pro", "Order") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='shipment_address'>{{ $t("craftable-pro", "Destination") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='picked_up_time'>{{ $t("craftable-pro", "Timeline") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='shipped_time'>{{ $t("craftable-pro", "Status") }}</ListingHeaderCell>
                <ListingHeaderCell><span class="sr-only">{{ $t("craftable-pro", "Actions") }}</span></ListingHeaderCell>
            </template>

            <template #tableRow="{ item, action }: any">
                <!-- Shipment: initials avatar + reference + handler -->
                <ListingDataCell>
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-primary-500/10 text-[11px] font-bold uppercase text-primary-600 dark:text-primary-400">
                            SH
                        </span>
                        <div class="flex flex-col">
                            <span class="font-medium text-gray-900 dark:text-white">#SHP-{{ String(item.id).padStart(5, '0') }}</span>
                            <span class="text-xs text-gray-400">{{ item.created_at ? dayjs(item.created_at).format('DD MMM YYYY') : '—' }}</span>
                        </div>
                    </div>
                </ListingDataCell>

                <!-- Order -->
                <ListingDataCell>
                    <div class="flex flex-col leading-tight">
                        <span class="text-sm text-gray-900 dark:text-white">{{ item.order?.order_no || '—' }}</span>
                        <span class="text-xs text-gray-400">{{ item.store?.name || '' }}</span>
                    </div>
                </ListingDataCell>

                <!-- Destination: address + city + postal code -->
                <ListingDataCell>
                    <div class="flex flex-col leading-tight">
                        <span class="block max-w-[220px] truncate text-sm text-gray-700 dark:text-gray-200" :title="item.shipment_address">{{ item.shipment_address || '—' }}</span>
                        <span class="text-xs text-gray-400">{{ [item.shipment_city?.name, item.postal_code].filter(Boolean).join(' · ') }}</span>
                    </div>
                </ListingDataCell>

                <!-- Timeline: picked up / shipped -->
                <ListingDataCell>
                    <div class="flex flex-col leading-tight">
                        <span class="text-sm text-gray-600 dark:text-gray-300">{{ item.picked_up_time ? dayjs(item.picked_up_time).format('DD MMM YYYY') : 'Not picked up' }}</span>
                        <span class="text-xs text-gray-400">{{ item.shipped_time ? 'Shipped ' + dayjs(item.shipped_time).format('DD MMM YYYY') : 'Not shipped' }}</span>
                    </div>
                </ListingDataCell>

                <!-- Status pill (derived from timeline) -->
                <ListingDataCell>
                    <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-medium" :class="statusOf(item).cls">
                        <span class="h-1.5 w-1.5 rounded-full" :class="statusOf(item).dot"></span>
                        {{ statusOf(item).label }}
                    </span>
                </ListingDataCell>

                <!-- Actions: rounded icon buttons (Larkon) -->
                <ListingDataCell>
                    <div class="flex items-center justify-center gap-2">
                        <Link :href="route('craftable-pro.shipments.edit', item)" title="View"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500 transition-colors hover:bg-gray-200 dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10">
                            <EyeIcon class="h-4 w-4" />
                        </Link>
                        <Link :href="route('craftable-pro.shipments.edit', item)" title="Edit" v-can="'craftable-pro.shipments.edit'"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary-600 transition-colors hover:bg-primary-100 dark:bg-primary-500/10 dark:text-primary-400 dark:hover:bg-primary-500/20">
                            <PencilSquareIcon class="h-4 w-4" />
                        </Link>
                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <button @click="() => setIsOpen(true)" title="Delete" v-can="'craftable-pro.shipments.destroy'"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 transition-colors hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20">
                                    <TrashIcon class="h-4 w-4" />
                                </button>
                            </template>
                            <template #title>{{ $t("craftable-pro", "Delete Shipment") }}</template>
                            <template #content>
                                {{ $t("craftable-pro", "Are you sure you want to delete selected Shipment? All data will be permanently removed from our servers forever. This action cannot be undone.") }}
                            </template>
                            <template #buttons="{ setIsOpen }">
                                <Button @click.prevent="() => { action('delete', route('craftable-pro.shipments.destroy', item), { onFinish: () => setIsOpen(false) }); }" color="danger" v-can="'craftable-pro.shipments.destroy'">
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
import type { Shipment } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat)

const statusOf = (item: any) => {
    if (item.shipped_time)
        return { label: "Shipped", dot: "bg-green-500", cls: "bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400" };
    if (item.picked_up_time)
        return { label: "In transit", dot: "bg-amber-500", cls: "bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400" };
    return { label: "Pending", dot: "bg-gray-400", cls: "bg-gray-100 text-gray-500 dark:bg-white/5 dark:text-gray-400" };
};

interface Props {
    shipments: PaginatedCollection<Shipment>;
}
defineProps<Props>();
</script>
