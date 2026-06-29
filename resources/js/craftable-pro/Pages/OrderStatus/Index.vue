<template>
    <PageHeader :title="$t('craftable-pro', 'Order Statuses')">
        <Button
            :leftIcon="PlusIcon"
            :as="Link"
            :href="route('craftable-pro.order-statuses.create')"
            v-can="'craftable-pro.order-statuses.create'"
        >
            {{ $t("craftable-pro", "New Order Status") }}
        </Button>
        
    </PageHeader>

    <PageContent>
        <Listing
            :baseUrl="route('craftable-pro.order-statuses.index')"
            :data="orderStatuses"
            dataKey="orderStatuses"
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
                            v-can="'craftable-pro.order-statuses.destroy'"
                        >
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                    </template>

                    <template #title>
                        {{ $t("craftable-pro", "Delete Order Status") }}
                    </template>

                    <template #content>
                        {{
                            $t(
                                "craftable-pro",
                                "Are you sure you want to delete selected Order Status? All data will be permanently removed from our servers forever. This action cannot be undone."
                            )
                        }}
                    </template>

                    <template #buttons="{ setIsOpen }">
                        <Button
                            @click.prevent="
                                () => {
                                    bulkAction('post', route('craftable-pro.order-statuses.bulk-destroy'), {
                                        onFinish: () => setIsOpen(false),
                                    });
                                }
                            "
                            color="danger"
                            v-can="'craftable-pro.order-statuses.destroy'"
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
                <ListingHeaderCell sortBy='name'>
                    {{ $t("craftable-pro", "Name") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='description'>
                    {{ $t("craftable-pro", "Description") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='is_default'>
                    {{ $t("craftable-pro", "Is Default") }}
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
                    <span class="font-medium text-gray-900 dark:text-white">{{ item.name }}</span>
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.description }}
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="is_default"
                        v-model="item.is_default"
                        :updateUrl="route('craftable-pro.order-statuses.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                    <span class="text-sm text-gray-500">{{ item.created_at && dayjs(item.created_at).format('DD MMM YYYY') }}</span>
                </ListingDataCell>
                <ListingDataCell>
                    <div class="flex items-center justify-end gap-3">
                        <IconButton
                            :as="Link"
                            :href="route('craftable-pro.order-statuses.edit', item)"
                            variant="ghost"
                            color="gray"
                            :icon="PencilSquareIcon"
                            v-can="'craftable-pro.order-statuses.edit'"
                        />

                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <IconButton
                                    @click="() => setIsOpen(true)"
                                    color="gray"
                                    variant="ghost"
                                    :icon="TrashIcon"
                                    v-can="'craftable-pro.order-statuses.destroy'"
                                />
                            </template>

                            <template #title>
                                {{ $t("craftable-pro", "Delete Order Status") }}
                            </template>

                            <template #content>
                                {{
                                    $t(
                                        "craftable-pro",
                                        "Are you sure you want to delete selected Order Status? All data will be permanently removed from our servers forever. This action cannot be undone."
                                    )
                                }}
                            </template>

                            <template #buttons="{ setIsOpen }">
                                <Button
                                    @click.prevent="
                                        () => {
                                            action('delete', route('craftable-pro.order-statuses.destroy', item), {
                                                onFinish: () => setIsOpen(false),
                                            });
                                        }
                                    "
                                    color="danger"
                                    v-can="'craftable-pro.order-statuses.destroy'"
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
import type { OrderStatus } from "./types";
import type { PageProps } from "craftable-pro/types/page";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat)



interface Props {
    orderStatuses: PaginatedCollection<OrderStatus>;
}
defineProps<Props>();

</script>
