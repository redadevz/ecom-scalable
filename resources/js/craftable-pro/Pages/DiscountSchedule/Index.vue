<template>
    <PageHeader :title="$t('craftable-pro', 'Discount Schedules')">
        <Button
            :leftIcon="PlusIcon"
            :as="Link"
            :href="route('craftable-pro.discount-schedules.create')"
            v-can="'craftable-pro.discount-schedules.create'"
        >
            {{ $t("craftable-pro", "New Discount Schedule") }}
        </Button>
    </PageHeader>

    <PageContent>
        <Listing
            :baseUrl="route('craftable-pro.discount-schedules.index')"
            :data="discountSchedules"
            dataKey="discountSchedules"
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
                            v-can="'craftable-pro.discount-schedules.destroy'"
                        >
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                    </template>

                    <template #title>
                        {{ $t("craftable-pro", "Delete Discount Schedule") }}
                    </template>

                    <template #content>
                        {{
                            $t(
                                "craftable-pro",
                                "Are you sure you want to delete selected Discount Schedule? All data will be permanently removed from our servers forever. This action cannot be undone."
                            )
                        }}
                    </template>

                    <template #buttons="{ setIsOpen }">
                        <Button
                            @click.prevent="
                                () => {
                                    bulkAction('post', route('craftable-pro.discount-schedules.bulk-destroy'), {
                                        onFinish: () => setIsOpen(false),
                                    });
                                }
                            "
                            color="danger"
                            v-can="'craftable-pro.discount-schedules.destroy'"
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
                <ListingHeaderCell sortBy='discount_type_id'>{{ $t("craftable-pro", "Discount") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='start_time'>{{ $t("craftable-pro", "Schedule window") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='comments'>{{ $t("craftable-pro", "Comments") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='day_of_week'>{{ $t("craftable-pro", "Status") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='created_at'>{{ $t("craftable-pro", "Created At") }}</ListingHeaderCell>
                <ListingHeaderCell><span class="sr-only">{{ $t("craftable-pro", "Actions") }}</span></ListingHeaderCell>
            </template>

            <template #tableRow="{ item, action }: any">
                <!-- Discount: initials avatar + discount type name + schedule id -->
                <ListingDataCell>
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-primary-500/10 text-sm font-bold uppercase text-primary-600 dark:text-primary-400">
                            {{ (item.discount_type?.name || '?').slice(0, 2) }}
                        </span>
                        <div class="flex flex-col">
                            <span class="font-medium text-gray-900 dark:text-white">{{ item.discount_type?.name || '—' }}</span>
                            <span class="text-xs text-gray-400">#{{ item.id }}</span>
                        </div>
                    </div>
                </ListingDataCell>

                <!-- Schedule window: start → end time -->
                <ListingDataCell>
                    <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
                        <span>{{ item.start_time ? dayjs(item.start_time, 'HH:mm:ss').format('HH:mm') : '—' }}</span>
                        <span class="text-gray-400">&rarr;</span>
                        <span>{{ item.end_time ? dayjs(item.end_time, 'HH:mm:ss').format('HH:mm') : '—' }}</span>
                    </div>
                </ListingDataCell>

                <!-- Comments -->
                <ListingDataCell>
                    <span class="block max-w-[240px] truncate text-sm text-gray-600 dark:text-gray-300" :title="item.comments">{{ item.comments || '—' }}</span>
                </ListingDataCell>

                <!-- Status toggle (day of week flag) -->
                <ListingDataCell>
                    <ListingToggle
                        name="day_of_week"
                        v-model="item.day_of_week"
                        :updateUrl="route('craftable-pro.discount-schedules.update', item.id)"
                    />
                </ListingDataCell>

                <!-- Created at -->
                <ListingDataCell>
                    <span class="text-sm text-gray-500">{{ item.created_at && dayjs(item.created_at).format('DD MMM YYYY') }}</span>
                </ListingDataCell>

                <!-- Actions: rounded icon buttons (Larkon) -->
                <ListingDataCell>
                    <div class="flex items-center justify-center gap-2">
                        <Link :href="route('craftable-pro.discount-schedules.edit', item)" title="View"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500 transition-colors hover:bg-gray-200 dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10">
                            <EyeIcon class="h-4 w-4" />
                        </Link>
                        <Link :href="route('craftable-pro.discount-schedules.edit', item)" title="Edit" v-can="'craftable-pro.discount-schedules.edit'"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary-600 transition-colors hover:bg-primary-100 dark:bg-primary-500/10 dark:text-primary-400 dark:hover:bg-primary-500/20">
                            <PencilSquareIcon class="h-4 w-4" />
                        </Link>
                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <button @click="() => setIsOpen(true)" title="Delete" v-can="'craftable-pro.discount-schedules.destroy'"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 transition-colors hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20">
                                    <TrashIcon class="h-4 w-4" />
                                </button>
                            </template>
                            <template #title>{{ $t("craftable-pro", "Delete Discount Schedule") }}</template>
                            <template #content>
                                {{ $t("craftable-pro", "Are you sure you want to delete selected Discount Schedule? All data will be permanently removed from our servers forever. This action cannot be undone.") }}
                            </template>
                            <template #buttons="{ setIsOpen }">
                                <Button @click.prevent="() => { action('delete', route('craftable-pro.discount-schedules.destroy', item), { onFinish: () => setIsOpen(false) }); }" color="danger" v-can="'craftable-pro.discount-schedules.destroy'">
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
import type { DiscountSchedule } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat)

interface Props {
    discountSchedules: PaginatedCollection<DiscountSchedule>;
}
defineProps<Props>();
</script>
