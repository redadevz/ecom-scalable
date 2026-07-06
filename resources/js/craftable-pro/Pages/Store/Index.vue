<template>
    <PageHeader :title="$t('craftable-pro', 'Stores')">
        <Button
            :leftIcon="PlusIcon"
            :as="Link"
            :href="route('craftable-pro.stores.create')"
            v-can="'craftable-pro.stores.create'"
        >
            {{ $t("craftable-pro", "New Store") }}
        </Button>
    </PageHeader>

    <PageContent>
        <Listing
            :baseUrl="route('craftable-pro.stores.index')"
            :data="stores"
            dataKey="stores"
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
                            v-can="'craftable-pro.stores.destroy'"
                        >
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                    </template>

                    <template #title>
                        {{ $t("craftable-pro", "Delete Store") }}
                    </template>

                    <template #content>
                        {{
                            $t(
                                "craftable-pro",
                                "Are you sure you want to delete selected Store? All data will be permanently removed from our servers forever. This action cannot be undone."
                            )
                        }}
                    </template>

                    <template #buttons="{ setIsOpen }">
                        <Button
                            @click.prevent="
                                () => {
                                    bulkAction('post', route('craftable-pro.stores.bulk-destroy'), {
                                        onFinish: () => setIsOpen(false),
                                    });
                                }
                            "
                            color="danger"
                            v-can="'craftable-pro.stores.destroy'"
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
                <ListingHeaderCell sortBy='name'>{{ $t("craftable-pro", "Store") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='legal_entity_name'>{{ $t("craftable-pro", "Legal entity") }}</ListingHeaderCell>
                <ListingHeaderCell>{{ $t("craftable-pro", "Contact") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='address'>{{ $t("craftable-pro", "Address") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='is_active'>{{ $t("craftable-pro", "Status") }}</ListingHeaderCell>
                <ListingHeaderCell><span class="sr-only">{{ $t("craftable-pro", "Actions") }}</span></ListingHeaderCell>
            </template>

            <template #tableRow="{ item, action }: any">
                <!-- Store: logo/initials + name + code -->
                <ListingDataCell>
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-primary-500/10 text-sm font-bold uppercase text-primary-600 dark:text-primary-400">
                            <img v-if="item.logo" :src="item.logo" :alt="item.name" class="h-full w-full object-cover" />
                            <template v-else>{{ (item.code || item.name || '?').slice(0, 2) }}</template>
                        </span>
                        <div class="flex flex-col">
                            <span class="font-medium text-gray-900 dark:text-white">{{ item.name }}</span>
                            <span class="text-xs text-gray-400">{{ item.code }}</span>
                        </div>
                    </div>
                </ListingDataCell>

                <!-- Legal entity + tax code -->
                <ListingDataCell>
                    <div class="flex flex-col leading-tight">
                        <span class="text-sm text-gray-900 dark:text-white">{{ item.legal_entity_name || '—' }}</span>
                        <span class="text-xs text-gray-400">{{ item.tax_code ? 'Tax ' + item.tax_code : '' }}</span>
                    </div>
                </ListingDataCell>

                <!-- Contact: phone + email -->
                <ListingDataCell>
                    <div class="flex flex-col leading-tight">
                        <span class="text-sm text-gray-700 dark:text-gray-200">{{ item.phone || '—' }}</span>
                        <span class="text-xs text-gray-400">{{ item.email }}</span>
                    </div>
                </ListingDataCell>

                <!-- Address -->
                <ListingDataCell>
                    <span class="block max-w-[220px] truncate text-sm text-gray-600 dark:text-gray-300" :title="item.address">{{ item.address || '—' }}</span>
                </ListingDataCell>

                <!-- Status toggle -->
                <ListingDataCell>
                    <ListingToggle
                        name="is_active"
                        v-model="item.is_active"
                        :updateUrl="route('craftable-pro.stores.update', item.id)"
                    />
                </ListingDataCell>

                <!-- Actions: rounded icon buttons (Larkon) -->
                <ListingDataCell>
                    <div class="flex items-center justify-center gap-2">
                        <Link :href="route('craftable-pro.stores.edit', item)" title="View"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500 transition-colors hover:bg-gray-200 dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10">
                            <EyeIcon class="h-4 w-4" />
                        </Link>
                        <Link :href="route('craftable-pro.stores.edit', item)" title="Edit" v-can="'craftable-pro.stores.edit'"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary-600 transition-colors hover:bg-primary-100 dark:bg-primary-500/10 dark:text-primary-400 dark:hover:bg-primary-500/20">
                            <PencilSquareIcon class="h-4 w-4" />
                        </Link>
                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <button @click="() => setIsOpen(true)" title="Delete" v-can="'craftable-pro.stores.destroy'"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 transition-colors hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20">
                                    <TrashIcon class="h-4 w-4" />
                                </button>
                            </template>
                            <template #title>{{ $t("craftable-pro", "Delete Store") }}</template>
                            <template #content>
                                {{ $t("craftable-pro", "Are you sure you want to delete selected Store? All data will be permanently removed from our servers forever. This action cannot be undone.") }}
                            </template>
                            <template #buttons="{ setIsOpen }">
                                <Button @click.prevent="() => { action('delete', route('craftable-pro.stores.destroy', item), { onFinish: () => setIsOpen(false) }); }" color="danger" v-can="'craftable-pro.stores.destroy'">
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
    IconButton,
    ListingToggle,
} from "craftable-pro/Components";
import { PaginatedCollection } from "craftable-pro/types/pagination";
import type { Store } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat)

interface Props {
    stores: PaginatedCollection<Store>;
}
defineProps<Props>();
</script>
