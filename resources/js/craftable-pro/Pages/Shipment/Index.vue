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
                <ListingHeaderCell sortBy='picked_up_by'>
                    {{ $t("craftable-pro", "Picked Up By") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='shipment_address'>
                    {{ $t("craftable-pro", "Shipment Address") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='gps_location'>
                    {{ $t("craftable-pro", "Gps Location") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='postal_code'>
                    {{ $t("craftable-pro", "Postal Code") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='shipment_notes'>
                    {{ $t("craftable-pro", "Shipment Notes") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='picked_up_time'>
                    {{ $t("craftable-pro", "Picked Up Time") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='shipped_time'>
                    {{ $t("craftable-pro", "Shipped Time") }}
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
                    <span class="font-medium text-gray-900 dark:text-white">{{ item.picked_up_by }}</span>
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.shipment_address }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.gps_location }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.postal_code }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.shipment_notes }}
                </ListingDataCell>
                <ListingDataCell>
                    <span class="text-sm text-gray-500">{{ item.picked_up_time && dayjs(item.picked_up_time).format('DD MMM YYYY') }}</span>
                </ListingDataCell>
                <ListingDataCell>
                    <span class="text-sm text-gray-500">{{ item.shipped_time && dayjs(item.shipped_time).format('DD MMM YYYY') }}</span>
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
                            :href="route('craftable-pro.shipments.edit', item)"
                            variant="ghost"
                            color="gray"
                            :icon="PencilSquareIcon"
                            v-can="'craftable-pro.shipments.edit'"
                        />

                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <IconButton
                                    @click="() => setIsOpen(true)"
                                    color="gray"
                                    variant="ghost"
                                    :icon="TrashIcon"
                                    v-can="'craftable-pro.shipments.destroy'"
                                />
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
                                            action('delete', route('craftable-pro.shipments.destroy', item), {
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
import type { Shipment } from "./types";
import type { PageProps } from "craftable-pro/types/page";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat)



interface Props {
    shipments: PaginatedCollection<Shipment>;
}
defineProps<Props>();

</script>
