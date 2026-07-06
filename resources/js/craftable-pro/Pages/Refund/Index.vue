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
                <ListingHeaderCell sortBy='refund_no'>{{ $t("craftable-pro", "Refund") }}</ListingHeaderCell>
                <ListingHeaderCell>{{ $t("craftable-pro", "Related") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='amount'>{{ $t("craftable-pro", "Amount") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='refund_time'>{{ $t("craftable-pro", "Refund Time") }}</ListingHeaderCell>
                <ListingHeaderCell>{{ $t("craftable-pro", "Status") }}</ListingHeaderCell>
                <ListingHeaderCell><span class="sr-only">{{ $t("craftable-pro", "Actions") }}</span></ListingHeaderCell>
            </template>

            <template #tableRow="{ item, action }: any">
                <!-- Refund: initials avatar + refund_no + id -->
                <ListingDataCell>
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-primary-500/10 text-sm font-bold uppercase text-primary-600 dark:text-primary-400">
                            {{ (item.refund_no || 'R').toString().slice(0, 2) }}
                        </span>
                        <div class="flex flex-col">
                            <span class="font-medium text-gray-900 dark:text-white">{{ item.refund_no || '—' }}</span>
                            <span class="text-xs text-gray-400">#{{ item.id }}</span>
                        </div>
                    </div>
                </ListingDataCell>

                <!-- Related: payment method + sale return -->
                <ListingDataCell>
                    <div class="flex flex-col leading-tight">
                        <span class="text-sm text-gray-900 dark:text-white">{{ item.payment_method?.name || '—' }}</span>
                        <span class="text-xs text-gray-400">{{ item.sale_return?.description || (item.sale_return_id ? '#' + item.sale_return_id : '') }}</span>
                    </div>
                </ListingDataCell>

                <!-- Amount + cash paid/change -->
                <ListingDataCell>
                    <div class="flex flex-col leading-tight">
                        <span class="font-semibold text-gray-900 dark:text-white">{{ money(item.amount) }}</span>
                        <span class="text-xs text-gray-400">{{ $t("craftable-pro", "Paid") }} {{ money(item.cash_paid) }} · {{ $t("craftable-pro", "Change") }} {{ money(item.cash_change) }}</span>
                    </div>
                </ListingDataCell>

                <!-- Refund time -->
                <ListingDataCell>
                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ item.refund_time ? dayjs(item.refund_time).format('DD MMM YYYY') : '—' }}</span>
                </ListingDataCell>

                <!-- Status pill derived from refund_time -->
                <ListingDataCell>
                    <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium"
                        :class="item.refund_time ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-gray-100 text-gray-500 dark:bg-white/5 dark:text-gray-400'">
                        <span class="h-1.5 w-1.5 rounded-full" :class="item.refund_time ? 'bg-green-500' : 'bg-gray-400'"></span>
                        {{ item.refund_time ? $t("craftable-pro", "Refunded") : $t("craftable-pro", "Pending") }}
                    </span>
                </ListingDataCell>

                <!-- Actions: rounded icon buttons (Larkon) -->
                <ListingDataCell>
                    <div class="flex items-center justify-center gap-2">
                        <Link :href="route('craftable-pro.refunds.edit', item)" title="View"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500 transition-colors hover:bg-gray-200 dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10">
                            <EyeIcon class="h-4 w-4" />
                        </Link>
                        <Link :href="route('craftable-pro.refunds.edit', item)" title="Edit" v-can="'craftable-pro.refunds.edit'"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary-600 transition-colors hover:bg-primary-100 dark:bg-primary-500/10 dark:text-primary-400 dark:hover:bg-primary-500/20">
                            <PencilSquareIcon class="h-4 w-4" />
                        </Link>
                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <button @click="() => setIsOpen(true)" title="Delete" v-can="'craftable-pro.refunds.destroy'"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 transition-colors hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20">
                                    <TrashIcon class="h-4 w-4" />
                                </button>
                            </template>
                            <template #title>{{ $t("craftable-pro", "Delete Refund") }}</template>
                            <template #content>
                                {{ $t("craftable-pro", "Are you sure you want to delete selected Refund? All data will be permanently removed from our servers forever. This action cannot be undone.") }}
                            </template>
                            <template #buttons="{ setIsOpen }">
                                <Button @click.prevent="() => { action('delete', route('craftable-pro.refunds.destroy', item), { onFinish: () => setIsOpen(false) }); }" color="danger" v-can="'craftable-pro.refunds.destroy'">
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
import type { Refund } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat)

const money = (v: any) =>
    Number(v ?? 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + " DH";

interface Props {
    refunds: PaginatedCollection<Refund>;
}
defineProps<Props>();
</script>
