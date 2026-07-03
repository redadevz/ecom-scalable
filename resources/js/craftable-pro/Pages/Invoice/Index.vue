<template>
    <PageHeader :title="$t('craftable-pro', 'Invoices')">
        <Button
            :leftIcon="PlusIcon"
            :as="Link"
            :href="route('craftable-pro.invoices.create')"
            v-can="'craftable-pro.invoices.create'"
        >
            {{ $t("craftable-pro", "New Invoice") }}
        </Button>
    </PageHeader>

    <PageContent>
        <Listing
            :baseUrl="route('craftable-pro.invoices.index')"
            :data="invoices"
            dataKey="invoices"
        >
            <template #bulkActions="{ bulkAction }">
                <Modal type="danger">
                    <template #trigger="{ setIsOpen }">
                        <Button @click="() => setIsOpen(true)" color="gray" variant="outline" size="sm" :leftIcon="TrashIcon" v-can="'craftable-pro.invoices.destroy'">
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                    </template>
                    <template #title>{{ $t("craftable-pro", "Delete Invoice") }}</template>
                    <template #content>{{ $t("craftable-pro", "Are you sure? This action cannot be undone.") }}</template>
                    <template #buttons="{ setIsOpen }">
                        <Button @click.prevent="() => { bulkAction('post', route('craftable-pro.invoices.bulk-destroy'), { onFinish: () => setIsOpen(false) }); }" color="danger" v-can="'craftable-pro.invoices.destroy'">
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                        <Button @click.prevent="() => setIsOpen()" color="gray" variant="outline">{{ $t("craftable-pro", "Cancel") }}</Button>
                    </template>
                </Modal>
            </template>

            <template #tableHead>
                <ListingHeaderCell sortBy='invoice_no'>{{ $t("craftable-pro", "Invoice No") }}</ListingHeaderCell>
                <ListingHeaderCell>{{ $t("craftable-pro", "Order") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='is_paid'>{{ $t("craftable-pro", "Paid") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='payment_time'>{{ $t("craftable-pro", "Payment Time") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='created_at'>{{ $t("craftable-pro", "Created") }}</ListingHeaderCell>
                <ListingHeaderCell><span class="sr-only">{{ $t("craftable-pro", "Actions") }}</span></ListingHeaderCell>
            </template>

            <template #tableRow="{ item, action }: any">
                <ListingDataCell>
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-500 dark:bg-white/5 dark:text-gray-300">
                            <DocumentTextIcon class="h-5 w-5" />
                        </span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ item.invoice_no }}</span>
                    </div>
                </ListingDataCell>
                <ListingDataCell>
                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ item.order?.order_no ?? '—' }}</span>
                </ListingDataCell>
                <ListingDataCell>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" :class="item.is_paid ? paidClass : unpaidClass">
                        {{ item.is_paid ? $t("craftable-pro", "Paid") : $t("craftable-pro", "Unpaid") }}
                    </span>
                </ListingDataCell>
                <ListingDataCell>
                    <span class="text-sm text-gray-500">{{ item.payment_time && dayjs(item.payment_time).format('DD MMM YYYY') }}</span>
                </ListingDataCell>
                <ListingDataCell>
                    <span class="text-sm text-gray-500">{{ item.created_at && dayjs(item.created_at).format('DD MMM YYYY') }}</span>
                </ListingDataCell>
                <ListingDataCell>
                    <div class="flex items-center justify-center gap-2">
                        <Link :href="route('craftable-pro.invoices.edit', item)" title="View"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500 transition-colors hover:bg-gray-200 dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10">
                            <EyeIcon class="h-4 w-4" />
                        </Link>
                        <Link :href="route('craftable-pro.invoices.edit', item)" title="Edit" v-can="'craftable-pro.invoices.edit'"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary-600 transition-colors hover:bg-primary-100 dark:bg-primary-500/10 dark:text-primary-400 dark:hover:bg-primary-500/20">
                            <PencilSquareIcon class="h-4 w-4" />
                        </Link>
                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <button @click="() => setIsOpen(true)" title="Delete" v-can="'craftable-pro.invoices.destroy'"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 transition-colors hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20">
                                    <TrashIcon class="h-4 w-4" />
                                </button>
                            </template>
                            <template #title>{{ $t("craftable-pro", "Delete Invoice") }}</template>
                            <template #content>{{ $t("craftable-pro", "Are you sure? This action cannot be undone.") }}</template>
                            <template #buttons="{ setIsOpen }">
                                <Button @click.prevent="() => { action('delete', route('craftable-pro.invoices.destroy', item), { onFinish: () => setIsOpen(false) }); }" color="danger" v-can="'craftable-pro.invoices.destroy'">
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
import { PlusIcon, TrashIcon, PencilSquareIcon, EyeIcon, DocumentTextIcon } from "@heroicons/vue/24/outline";
import {
    PageHeader, PageContent, Button, Listing,
    ListingHeaderCell, ListingDataCell, Modal, IconButton,
} from "craftable-pro/Components";
import { PaginatedCollection } from "craftable-pro/types/pagination";
import type { Invoice } from "./types";
import dayjs from "dayjs";

const paidClass = "bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-400";
const unpaidClass = "bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400";

interface Props {
    invoices: PaginatedCollection<Invoice>;
}
defineProps<Props>();
</script>
