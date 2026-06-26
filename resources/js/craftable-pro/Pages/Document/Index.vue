<template>
    <PageHeader :title="$t('craftable-pro', 'Documents')">
        <Button
            :leftIcon="PlusIcon"
            :as="Link"
            :href="route('craftable-pro.documents.create')"
            v-can="'craftable-pro.documents.create'"
        >
            {{ $t("craftable-pro", "New Document") }}
        </Button>
        
    </PageHeader>

    <PageContent>
        <Listing
            :baseUrl="route('craftable-pro.documents.index')"
            :data="documents"
            dataKey="documents"
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
                            v-can="'craftable-pro.documents.destroy'"
                        >
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                    </template>

                    <template #title>
                        {{ $t("craftable-pro", "Delete Document") }}
                    </template>

                    <template #content>
                        {{
                            $t(
                                "craftable-pro",
                                "Are you sure you want to delete selected Document? All data will be permanently removed from our servers forever. This action cannot be undone."
                            )
                        }}
                    </template>

                    <template #buttons="{ setIsOpen }">
                        <Button
                            @click.prevent="
                                () => {
                                    bulkAction('post', route('craftable-pro.documents.bulk-destroy'), {
                                        onFinish: () => setIsOpen(false),
                                    });
                                }
                            "
                            color="danger"
                            v-can="'craftable-pro.documents.destroy'"
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
                <ListingHeaderCell sortBy='document_type_id'>
                    {{ $t("craftable-pro", "Document Type Id") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='sale_order_id'>
                    {{ $t("craftable-pro", "Sale Order Id") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='purchase_id'>
                    {{ $t("craftable-pro", "Purchase Id") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='stock_return_id'>
                    {{ $t("craftable-pro", "Stock Return Id") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='inventory_count_id'>
                    {{ $t("craftable-pro", "Inventory Count Id") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='loss_and_damage_id'>
                    {{ $t("craftable-pro", "Loss And Damage Id") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='created_by'>
                    {{ $t("craftable-pro", "Created By") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='number'>
                    {{ $t("craftable-pro", "Number") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='external_number'>
                    {{ $t("craftable-pro", "External Number") }}
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
                     {{ item.id }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.store_id }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.document_type_id }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.sale_order_id }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.purchase_id }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.stock_return_id }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.inventory_count_id }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.loss_and_damage_id }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.created_by }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.number }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.external_number }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.description }}
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
                            :href="route('craftable-pro.documents.edit', item)"
                            variant="ghost"
                            color="gray"
                            :icon="PencilSquareIcon"
                            v-can="'craftable-pro.documents.edit'"
                        />

                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <IconButton
                                    @click="() => setIsOpen(true)"
                                    color="gray"
                                    variant="ghost"
                                    :icon="TrashIcon"
                                    v-can="'craftable-pro.documents.destroy'"
                                />
                            </template>

                            <template #title>
                                {{ $t("craftable-pro", "Delete Document") }}
                            </template>

                            <template #content>
                                {{
                                    $t(
                                        "craftable-pro",
                                        "Are you sure you want to delete selected Document? All data will be permanently removed from our servers forever. This action cannot be undone."
                                    )
                                }}
                            </template>

                            <template #buttons="{ setIsOpen }">
                                <Button
                                    @click.prevent="
                                        () => {
                                            action('delete', route('craftable-pro.documents.destroy', item), {
                                                onFinish: () => setIsOpen(false),
                                            });
                                        }
                                    "
                                    color="danger"
                                    v-can="'craftable-pro.documents.destroy'"
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
import type { Document } from "./types";
import type { PageProps } from "craftable-pro/types/page";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat)



interface Props {
    documents: PaginatedCollection<Document>;
}
defineProps<Props>();

</script>
