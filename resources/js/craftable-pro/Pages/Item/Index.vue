<template>
    <PageHeader :title="$t('craftable-pro', 'Items')">
        <Button
            :leftIcon="PlusIcon"
            :as="Link"
            :href="route('craftable-pro.items.create')"
            v-can="'craftable-pro.items.create'"
        >
            {{ $t("craftable-pro", "New Item") }}
        </Button>
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
                <ListingHeaderCell sortBy='name'>
                    {{ $t("craftable-pro", "Item") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='current_stock_quantity'>
                    {{ $t("craftable-pro", "Stock") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='is_service'>
                    {{ $t("craftable-pro", "Type") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='in_stock'>
                    {{ $t("craftable-pro", "In Stock") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='is_active'>
                    {{ $t("craftable-pro", "Active") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='created_at'>
                    {{ $t("craftable-pro", "Created") }}
                </ListingHeaderCell>
                <ListingHeaderCell>
                    <span class="sr-only">{{ $t("craftable-pro", "Actions") }}</span>
                </ListingHeaderCell>
            </template>

            <template #tableRow="{ item, action }: any">
                <!-- Item: avatar + name + SKU -->
                <ListingDataCell>
                    <div class="flex items-center gap-3">
                        <span
                            class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg text-xs font-bold uppercase ring-1 ring-inset"
                            :class="avatarClass(item.name)"
                        >
                            {{ (item.name || '?').slice(0, 2) }}
                        </span>
                        <div class="flex flex-col">
                            <span class="font-medium text-gray-900 dark:text-white">{{ item.name }}</span>
                            <span class="text-xs text-gray-400">{{ item.sku_code }}</span>
                        </div>
                    </div>
                </ListingDataCell>

                <!-- Stock with low-stock highlight -->
                <ListingDataCell>
                    <span
                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold tabular-nums"
                        :class="
                            item.current_stock_quantity <= item.low_stock_quantity
                                ? 'bg-danger-50 text-danger-700 dark:bg-danger-500/10 dark:text-danger-400'
                                : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300'
                        "
                    >
                        {{ item.current_stock_quantity ?? 0 }}
                    </span>
                </ListingDataCell>

                <!-- Type pill: Service vs Product -->
                <ListingDataCell>
                    <span
                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                        :class="
                            item.is_service
                                ? 'bg-info-50 text-info-700 dark:bg-info-500/10 dark:text-info-400'
                                : 'bg-secondary-50 text-secondary-700 dark:bg-secondary-500/10 dark:text-secondary-400'
                        "
                    >
                        {{ item.is_service ? $t("craftable-pro", "Service") : $t("craftable-pro", "Product") }}
                    </span>
                </ListingDataCell>

                <!-- In Stock toggle -->
                <ListingDataCell>
                    <ListingToggle
                        name="in_stock"
                        v-model="item.in_stock"
                        :updateUrl="route('craftable-pro.items.update', item.id)"
                    />
                </ListingDataCell>

                <!-- Active toggle -->
                <ListingDataCell>
                    <ListingToggle
                        name="is_active"
                        v-model="item.is_active"
                        :updateUrl="route('craftable-pro.items.update', item.id)"
                    />
                </ListingDataCell>

                <!-- Created -->
                <ListingDataCell>
                    <span class="text-sm text-gray-500">
                        {{ item.created_at && dayjs(item.created_at).format('DD MMM YYYY') }}
                    </span>
                </ListingDataCell>

                <!-- Actions -->
                <ListingDataCell>
                    <div class="flex items-center justify-end gap-3">
                        <IconButton
                            :as="Link"
                            :href="route('craftable-pro.items.edit', item)"
                            variant="ghost"
                            color="gray"
                            :icon="PencilSquareIcon"
                            v-can="'craftable-pro.items.edit'"
                        />

                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <IconButton
                                    @click="() => setIsOpen(true)"
                                    color="gray"
                                    variant="ghost"
                                    :icon="TrashIcon"
                                    v-can="'craftable-pro.items.destroy'"
                                />
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
                                            action('delete', route('craftable-pro.items.destroy', item), {
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
import type { Item } from "./types";
import type { PageProps } from "craftable-pro/types/page";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat)



// deterministic pastel avatar color from the item name
const avatarClass = (_name: string) =>
    "bg-gray-100 text-gray-600 ring-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-700";

interface Props {
    items: PaginatedCollection<Item>;
}
defineProps<Props>();

</script>
