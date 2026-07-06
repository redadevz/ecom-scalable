<template>
    <PageHeader :title="$t('craftable-pro', 'Suppliers')">
        <Button
            :leftIcon="PlusIcon"
            :as="Link"
            :href="route('craftable-pro.suppliers.create')"
            v-can="'craftable-pro.suppliers.create'"
        >
            {{ $t("craftable-pro", "New Supplier") }}
        </Button>
    </PageHeader>

    <PageContent>
        <Listing
            :baseUrl="route('craftable-pro.suppliers.index')"
            :data="suppliers"
            dataKey="suppliers"
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
                            v-can="'craftable-pro.suppliers.destroy'"
                        >
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                    </template>

                    <template #title>
                        {{ $t("craftable-pro", "Delete Supplier") }}
                    </template>

                    <template #content>
                        {{
                            $t(
                                "craftable-pro",
                                "Are you sure you want to delete selected Supplier? All data will be permanently removed from our servers forever. This action cannot be undone."
                            )
                        }}
                    </template>

                    <template #buttons="{ setIsOpen }">
                        <Button
                            @click.prevent="
                                () => {
                                    bulkAction('post', route('craftable-pro.suppliers.bulk-destroy'), {
                                        onFinish: () => setIsOpen(false),
                                    });
                                }
                            "
                            color="danger"
                            v-can="'craftable-pro.suppliers.destroy'"
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
                <ListingHeaderCell sortBy='first_name'>{{ $t("craftable-pro", "Supplier") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='is_company'>{{ $t("craftable-pro", "Type") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='phone'>{{ $t("craftable-pro", "Contact") }}</ListingHeaderCell>
                <ListingHeaderCell>{{ $t("craftable-pro", "City") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='is_active'>{{ $t("craftable-pro", "Status") }}</ListingHeaderCell>
                <ListingHeaderCell><span class="sr-only">{{ $t("craftable-pro", "Actions") }}</span></ListingHeaderCell>
            </template>

            <template #tableRow="{ item, action }: any">
                <!-- Supplier: initials avatar + display name + code -->
                <ListingDataCell>
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-primary-500/10 text-sm font-bold uppercase text-primary-600 dark:text-primary-400">
                            {{ initials(displayName(item)) }}
                        </span>
                        <div class="flex flex-col">
                            <span class="font-medium text-gray-900 dark:text-white">{{ displayName(item) }}</span>
                            <span class="text-xs text-gray-400">{{ item.code || '—' }}</span>
                        </div>
                    </div>
                </ListingDataCell>

                <!-- Type: Company vs Individual pill -->
                <ListingDataCell>
                    <span
                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                        :class="item.is_company
                            ? 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400'
                            : 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-300'"
                    >
                        {{ item.is_company ? $t("craftable-pro", "Company") : $t("craftable-pro", "Individual") }}
                    </span>
                </ListingDataCell>

                <!-- Contact: phone + email -->
                <ListingDataCell>
                    <div class="flex flex-col leading-tight">
                        <span class="text-sm text-gray-700 dark:text-gray-200">{{ item.phone || '—' }}</span>
                        <span class="text-xs text-gray-400">{{ item.email }}</span>
                    </div>
                </ListingDataCell>

                <!-- City -->
                <ListingDataCell>
                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ item.city?.name || '—' }}</span>
                </ListingDataCell>

                <!-- Status toggle -->
                <ListingDataCell>
                    <ListingToggle
                        name="is_active"
                        v-model="item.is_active"
                        :updateUrl="route('craftable-pro.suppliers.update', item.id)"
                    />
                </ListingDataCell>

                <!-- Actions: rounded icon buttons (Larkon) -->
                <ListingDataCell>
                    <div class="flex items-center justify-center gap-2">
                        <Link :href="route('craftable-pro.suppliers.edit', item)" :title="$t('craftable-pro', 'View')"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500 transition-colors hover:bg-gray-200 dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10">
                            <EyeIcon class="h-4 w-4" />
                        </Link>
                        <Link :href="route('craftable-pro.suppliers.edit', item)" :title="$t('craftable-pro', 'Edit')" v-can="'craftable-pro.suppliers.edit'"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary-600 transition-colors hover:bg-primary-100 dark:bg-primary-500/10 dark:text-primary-400 dark:hover:bg-primary-500/20">
                            <PencilSquareIcon class="h-4 w-4" />
                        </Link>
                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <button @click="() => setIsOpen(true)" :title="$t('craftable-pro', 'Delete')" v-can="'craftable-pro.suppliers.destroy'"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 transition-colors hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20">
                                    <TrashIcon class="h-4 w-4" />
                                </button>
                            </template>
                            <template #title>{{ $t("craftable-pro", "Delete Supplier") }}</template>
                            <template #content>
                                {{ $t("craftable-pro", "Are you sure you want to delete selected Supplier? All data will be permanently removed from our servers forever. This action cannot be undone.") }}
                            </template>
                            <template #buttons="{ setIsOpen }">
                                <Button @click.prevent="() => { action('delete', route('craftable-pro.suppliers.destroy', item), { onFinish: () => setIsOpen(false) }); }" color="danger" v-can="'craftable-pro.suppliers.destroy'">
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
    ListingToggle,
} from "craftable-pro/Components";
import { PaginatedCollection } from "craftable-pro/types/pagination";
import type { Supplier } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat);

const displayName = (item: any): string => {
    if (item.is_company) {
        return item.company_name || "—";
    }
    return [item.first_name, item.last_name].filter(Boolean).join(" ") || "—";
};

const initials = (name: string): string => {
    const parts = (name || "").trim().split(/\s+/).filter(Boolean);
    if (parts.length === 0) return "?";
    if (parts.length === 1) return parts[0].slice(0, 2);
    return (parts[0][0] + parts[1][0]);
};

interface Props {
    suppliers: PaginatedCollection<Supplier>;
}
defineProps<Props>();
</script>
