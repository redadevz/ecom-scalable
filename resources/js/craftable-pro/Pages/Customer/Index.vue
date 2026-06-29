<template>
    <PageHeader :title="$t('craftable-pro', 'Customers')">
        <Button
            :leftIcon="PlusIcon"
            :as="Link"
            :href="route('craftable-pro.customers.create')"
            v-can="'craftable-pro.customers.create'"
        >
            {{ $t("craftable-pro", "New Customer") }}
        </Button>
    </PageHeader>

    <PageContent>
        <Listing
            :baseUrl="route('craftable-pro.customers.index')"
            :data="customers"
            dataKey="customers"
        >
            <template #bulkActions="{ bulkAction }">
                <Modal type="danger">
                    <template #trigger="{ setIsOpen }">
                        <Button @click="() => setIsOpen(true)" color="gray" variant="outline" size="sm" :leftIcon="TrashIcon" v-can="'craftable-pro.customers.destroy'">
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                    </template>
                    <template #title>{{ $t("craftable-pro", "Delete Customer") }}</template>
                    <template #content>{{ $t("craftable-pro", "Are you sure? This action cannot be undone.") }}</template>
                    <template #buttons="{ setIsOpen }">
                        <Button @click.prevent="() => { bulkAction('post', route('craftable-pro.customers.bulk-destroy'), { onFinish: () => setIsOpen(false) }); }" color="danger" v-can="'craftable-pro.customers.destroy'">
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                        <Button @click.prevent="() => setIsOpen()" color="gray" variant="outline">{{ $t("craftable-pro", "Cancel") }}</Button>
                    </template>
                </Modal>
            </template>

            <template #tableHead>
                <ListingHeaderCell sortBy='first_name'>{{ $t("craftable-pro", "Customer") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='phone'>{{ $t("craftable-pro", "Phone") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='email'>{{ $t("craftable-pro", "Email") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='credit'>{{ $t("craftable-pro", "Credit") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='is_active'>{{ $t("craftable-pro", "Active") }}</ListingHeaderCell>
                <ListingHeaderCell><span class="sr-only">{{ $t("craftable-pro", "Actions") }}</span></ListingHeaderCell>
            </template>

            <template #tableRow="{ item, action }: any">
                <ListingDataCell>
                    <div class="flex flex-col">
                        <span class="font-medium text-gray-900 dark:text-white">{{ name(item) }}</span>
                        <span class="text-xs text-gray-400">{{ item.code }}</span>
                    </div>
                </ListingDataCell>
                <ListingDataCell>
                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ item.phone || '—' }}</span>
                </ListingDataCell>
                <ListingDataCell>
                    <span class="text-sm text-gray-500">{{ item.email || '—' }}</span>
                </ListingDataCell>
                <ListingDataCell>
                    <span class="tabular-nums font-medium" :class="Number(item.credit) > 0 ? 'text-success-600 dark:text-success-400' : 'text-gray-500'">
                        {{ money(item.credit) }}
                    </span>
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle name="is_active" v-model="item.is_active" :updateUrl="route('craftable-pro.customers.update', item.id)" />
                </ListingDataCell>
                <ListingDataCell>
                    <div class="flex items-center justify-end gap-3">
                        <IconButton :as="Link" :href="route('craftable-pro.customers.edit', item)" variant="ghost" color="gray" :icon="PencilSquareIcon" v-can="'craftable-pro.customers.edit'" />
                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <IconButton @click="() => setIsOpen(true)" color="gray" variant="ghost" :icon="TrashIcon" v-can="'craftable-pro.customers.destroy'" />
                            </template>
                            <template #title>{{ $t("craftable-pro", "Delete Customer") }}</template>
                            <template #content>{{ $t("craftable-pro", "Are you sure? This action cannot be undone.") }}</template>
                            <template #buttons="{ setIsOpen }">
                                <Button @click.prevent="() => { action('delete', route('craftable-pro.customers.destroy', item), { onFinish: () => setIsOpen(false) }); }" color="danger" v-can="'craftable-pro.customers.destroy'">
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
import { PlusIcon, TrashIcon, PencilSquareIcon } from "@heroicons/vue/24/outline";
import {
    PageHeader, PageContent, Button, Listing,
    ListingHeaderCell, ListingDataCell, Modal, IconButton, ListingToggle,
} from "craftable-pro/Components";
import { PaginatedCollection } from "craftable-pro/types/pagination";
import type { Customer } from "./types";

const money = (v: any) =>
    Number(v ?? 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + " DH";

const name = (c: any) =>
    (c.is_company ? c.company_name : `${c.first_name ?? ""} ${c.last_name ?? ""}`).trim() || "—";

interface Props {
    customers: PaginatedCollection<Customer>;
}
defineProps<Props>();
</script>
