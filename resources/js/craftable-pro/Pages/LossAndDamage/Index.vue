<template>
    <PageHeader :title="$t('craftable-pro', 'Loss And Damages')">
        <Button
            :leftIcon="PlusIcon"
            :as="Link"
            :href="route('craftable-pro.loss-and-damages.create')"
            v-can="'craftable-pro.loss-and-damages.create'"
        >
            {{ $t("craftable-pro", "New Loss And Damage") }}
        </Button>
    </PageHeader>

    <PageContent>
        <Listing
            :baseUrl="route('craftable-pro.loss-and-damages.index')"
            :data="lossAndDamages"
            dataKey="lossAndDamages"
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
                            v-can="'craftable-pro.loss-and-damages.destroy'"
                        >
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                    </template>

                    <template #title>
                        {{ $t("craftable-pro", "Delete Loss And Damage") }}
                    </template>

                    <template #content>
                        {{
                            $t(
                                "craftable-pro",
                                "Are you sure you want to delete selected Loss And Damage? All data will be permanently removed from our servers forever. This action cannot be undone."
                            )
                        }}
                    </template>

                    <template #buttons="{ setIsOpen }">
                        <Button
                            @click.prevent="
                                () => {
                                    bulkAction('post', route('craftable-pro.loss-and-damages.bulk-destroy'), {
                                        onFinish: () => setIsOpen(false),
                                    });
                                }
                            "
                            color="danger"
                            v-can="'craftable-pro.loss-and-damages.destroy'"
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
                <ListingHeaderCell sortBy='id'>{{ $t("craftable-pro", "Record") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='description'>{{ $t("craftable-pro", "Description") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='created_at'>{{ $t("craftable-pro", "Date") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='exit_stock_time'>{{ $t("craftable-pro", "Status") }}</ListingHeaderCell>
                <ListingHeaderCell><span class="sr-only">{{ $t("craftable-pro", "Actions") }}</span></ListingHeaderCell>
            </template>

            <template #tableRow="{ item, action }: any">
                <!-- Record: initials + code + store -->
                <ListingDataCell>
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-primary-500/10 text-sm font-bold uppercase text-primary-600 dark:text-primary-400">
                            {{ String(item.id).slice(0, 2) }}
                        </span>
                        <div class="flex flex-col">
                            <span class="font-medium text-gray-900 dark:text-white">{{ $t("craftable-pro", "L&D") }} #{{ item.id }}</span>
                            <span class="text-xs text-gray-400">{{ item.store?.name || '—' }}</span>
                        </div>
                    </div>
                </ListingDataCell>

                <!-- Description / reason -->
                <ListingDataCell>
                    <div class="flex flex-col leading-tight">
                        <span class="block max-w-[260px] truncate text-sm text-gray-900 dark:text-white" :title="item.description">{{ item.description || '—' }}</span>
                        <span v-if="item.comments" class="block max-w-[260px] truncate text-xs text-gray-400" :title="item.comments">{{ item.comments }}</span>
                    </div>
                </ListingDataCell>

                <!-- Date -->
                <ListingDataCell>
                    <span class="text-sm text-gray-500">{{ item.created_at && dayjs(item.created_at).format('DD MMM YYYY') }}</span>
                </ListingDataCell>

                <!-- Status pill -->
                <ListingDataCell>
                    <span
                        v-if="item.exit_stock_time"
                        class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 dark:bg-green-500/10 dark:text-green-400"
                    >
                        {{ $t("craftable-pro", "Processed") }}
                    </span>
                    <span
                        v-else
                        class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-700 dark:bg-amber-500/10 dark:text-amber-400"
                    >
                        {{ $t("craftable-pro", "Pending") }}
                    </span>
                </ListingDataCell>

                <!-- Actions: rounded icon buttons (Larkon) -->
                <ListingDataCell>
                    <div class="flex items-center justify-center gap-2">
                        <Link :href="route('craftable-pro.loss-and-damages.edit', item)" title="View"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500 transition-colors hover:bg-gray-200 dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10">
                            <EyeIcon class="h-4 w-4" />
                        </Link>
                        <Link :href="route('craftable-pro.loss-and-damages.edit', item)" title="Edit" v-can="'craftable-pro.loss-and-damages.edit'"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary-600 transition-colors hover:bg-primary-100 dark:bg-primary-500/10 dark:text-primary-400 dark:hover:bg-primary-500/20">
                            <PencilSquareIcon class="h-4 w-4" />
                        </Link>
                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <button @click="() => setIsOpen(true)" title="Delete" v-can="'craftable-pro.loss-and-damages.destroy'"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 transition-colors hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20">
                                    <TrashIcon class="h-4 w-4" />
                                </button>
                            </template>
                            <template #title>{{ $t("craftable-pro", "Delete Loss And Damage") }}</template>
                            <template #content>
                                {{ $t("craftable-pro", "Are you sure you want to delete selected Loss And Damage? All data will be permanently removed from our servers forever. This action cannot be undone.") }}
                            </template>
                            <template #buttons="{ setIsOpen }">
                                <Button @click.prevent="() => { action('delete', route('craftable-pro.loss-and-damages.destroy', item), { onFinish: () => setIsOpen(false) }); }" color="danger" v-can="'craftable-pro.loss-and-damages.destroy'">
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
import type { LossAndDamage } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat);

interface Props {
    lossAndDamages: PaginatedCollection<LossAndDamage>;
}
defineProps<Props>();
</script>
