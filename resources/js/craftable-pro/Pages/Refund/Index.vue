<template>
    <PageHeader :title="$t('craftable-pro', 'Refunds')">
        <Button
            :leftIcon="PlusIcon"
            :as="Link"
            :href="route('craftable-pro.refunds.create')"
            v-can="'craftable-pro.refunds.create'"
        >
            {{ $t("craftable-pro", "New Refund") }}
        </Button>
        
    </PageHeader>

    <PageContent>
        <Listing
            :baseUrl="route('craftable-pro.refunds.index')"
            :data="refunds"
            dataKey="refunds"
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
                            v-can="'craftable-pro.refunds.destroy'"
                        >
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                    </template>

                    <template #title>
                        {{ $t("craftable-pro", "Delete Refund") }}
                    </template>

                    <template #content>
                        {{
                            $t(
                                "craftable-pro",
                                "Are you sure you want to delete selected Refund? All data will be permanently removed from our servers forever. This action cannot be undone."
                            )
                        }}
                    </template>

                    <template #buttons="{ setIsOpen }">
                        <Button
                            @click.prevent="
                                () => {
                                    bulkAction('post', route('craftable-pro.refunds.bulk-destroy'), {
                                        onFinish: () => setIsOpen(false),
                                    });
                                }
                            "
                            color="danger"
                            v-can="'craftable-pro.refunds.destroy'"
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
                <ListingHeaderCell sortBy='refund_no'>
                    {{ $t("craftable-pro", "Refund No") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='amount'>
                    {{ $t("craftable-pro", "Amount") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='cash_paid'>
                    {{ $t("craftable-pro", "Cash Paid") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='cash_change'>
                    {{ $t("craftable-pro", "Cash Change") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='refund_time'>
                    {{ $t("craftable-pro", "Refund Time") }}
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
                    <span class="font-medium text-gray-900 dark:text-white">{{ item.refund_no }}</span>
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.amount }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.cash_paid }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.cash_change }}
                </ListingDataCell>
                <ListingDataCell>
                    <span class="text-sm text-gray-500">{{ item.refund_time && dayjs(item.refund_time).format('DD MMM YYYY') }}</span>
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
                            :href="route('craftable-pro.refunds.edit', item)"
                            variant="ghost"
                            color="gray"
                            :icon="PencilSquareIcon"
                            v-can="'craftable-pro.refunds.edit'"
                        />

                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <IconButton
                                    @click="() => setIsOpen(true)"
                                    color="gray"
                                    variant="ghost"
                                    :icon="TrashIcon"
                                    v-can="'craftable-pro.refunds.destroy'"
                                />
                            </template>

                            <template #title>
                                {{ $t("craftable-pro", "Delete Refund") }}
                            </template>

                            <template #content>
                                {{
                                    $t(
                                        "craftable-pro",
                                        "Are you sure you want to delete selected Refund? All data will be permanently removed from our servers forever. This action cannot be undone."
                                    )
                                }}
                            </template>

                            <template #buttons="{ setIsOpen }">
                                <Button
                                    @click.prevent="
                                        () => {
                                            action('delete', route('craftable-pro.refunds.destroy', item), {
                                                onFinish: () => setIsOpen(false),
                                            });
                                        }
                                    "
                                    color="danger"
                                    v-can="'craftable-pro.refunds.destroy'"
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
import type { Refund } from "./types";
import type { PageProps } from "craftable-pro/types/page";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat)



interface Props {
    refunds: PaginatedCollection<Refund>;
}
defineProps<Props>();

</script>
