<template>
    <PageHeader :title="$t('craftable-pro', 'Measure Units')">
        <Button
            :leftIcon="PlusIcon"
            @click="openCreate"
            v-can="'craftable-pro.measure-units.create'"
        >
            {{ $t("craftable-pro", "New Measure Unit") }}
        </Button>
    </PageHeader>

    <PageContent>
        <Listing
            :baseUrl="route('craftable-pro.measure-units.index')"
            :data="measureUnits"
            dataKey="measureUnits"
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
                            v-can="'craftable-pro.measure-units.destroy'"
                        >
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                    </template>

                    <template #title>
                        {{ $t("craftable-pro", "Delete Measure Unit") }}
                    </template>

                    <template #content>
                        {{
                            $t(
                                "craftable-pro",
                                "Are you sure you want to delete selected Measure Unit? All data will be permanently removed from our servers forever. This action cannot be undone."
                            )
                        }}
                    </template>

                    <template #buttons="{ setIsOpen }">
                        <Button
                            @click.prevent="
                                () => {
                                    bulkAction('post', route('craftable-pro.measure-units.bulk-destroy'), {
                                        onFinish: () => setIsOpen(false),
                                    });
                                }
                            "
                            color="danger"
                            v-can="'craftable-pro.measure-units.destroy'"
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
                <ListingHeaderCell sortBy='name'>{{ $t("craftable-pro", "Measure Unit") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='symbol'>{{ $t("craftable-pro", "Symbol") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='description'>{{ $t("craftable-pro", "Description") }}</ListingHeaderCell>
                <ListingHeaderCell sortBy='created_at'>{{ $t("craftable-pro", "Created At") }}</ListingHeaderCell>
                <ListingHeaderCell><span class="sr-only">{{ $t("craftable-pro", "Actions") }}</span></ListingHeaderCell>
            </template>

            <template #tableRow="{ item, action }: any">
                <!-- Measure Unit: initials + name + symbol -->
                <ListingDataCell>
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-primary-500/10 text-sm font-bold uppercase text-primary-600 dark:text-primary-400">
                            {{ (item.symbol || item.name || '?').slice(0, 2) }}
                        </span>
                        <div class="flex flex-col">
                            <span class="font-medium text-gray-900 dark:text-white">{{ item.name }}</span>
                            <span class="text-xs text-gray-400">{{ item.symbol }}</span>
                        </div>
                    </div>
                </ListingDataCell>

                <!-- Symbol badge -->
                <ListingDataCell>
                    <span v-if="item.symbol" class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 font-mono text-xs font-medium text-gray-700 dark:bg-white/5 dark:text-gray-200">{{ item.symbol }}</span>
                    <span v-else class="text-gray-400">—</span>
                </ListingDataCell>

                <!-- Description -->
                <ListingDataCell>
                    <span class="block max-w-[280px] truncate text-sm text-gray-600 dark:text-gray-300" :title="item.description">{{ item.description || '—' }}</span>
                </ListingDataCell>

                <!-- Created At -->
                <ListingDataCell>
                    <span class="text-sm text-gray-500">{{ item.created_at && dayjs(item.created_at).format('DD MMM YYYY') }}</span>
                </ListingDataCell>

                <!-- Actions: rounded icon buttons (Larkon) -->
                <ListingDataCell>
                    <div class="flex items-center justify-center gap-2">
                        <button type="button" @click="openEdit(item)" title="Edit" v-can="'craftable-pro.measure-units.edit'"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary-600 transition-colors hover:bg-primary-100 dark:bg-primary-500/10 dark:text-primary-400 dark:hover:bg-primary-500/20">
                            <PencilSquareIcon class="h-4 w-4" />
                        </button>
                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <button @click="() => setIsOpen(true)" title="Delete" v-can="'craftable-pro.measure-units.destroy'"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 transition-colors hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20">
                                    <TrashIcon class="h-4 w-4" />
                                </button>
                            </template>
                            <template #title>{{ $t("craftable-pro", "Delete Measure Unit") }}</template>
                            <template #content>
                                {{ $t("craftable-pro", "Are you sure you want to delete selected Measure Unit? All data will be permanently removed from our servers forever. This action cannot be undone.") }}
                            </template>
                            <template #buttons="{ setIsOpen }">
                                <Button @click.prevent="() => { action('delete', route('craftable-pro.measure-units.destroy', item), { onFinish: () => setIsOpen(false) }); }" color="danger" v-can="'craftable-pro.measure-units.destroy'">
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

    <!-- Create / Edit modal -->
    <Transition enter-active-class="transition duration-150" enter-from-class="opacity-0" leave-active-class="transition duration-100" leave-to-class="opacity-0">
        <div v-if="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50" @click="modalOpen = false"></div>
            <div class="relative w-full max-w-lg rounded-2xl border border-gray-200 bg-white shadow-xl dark:border-[#2c2f3d] dark:bg-[#1e2029]">
                <header class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-[#2a2d38]">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-500/10 text-primary-500"><ScaleIcon class="h-5 w-5" /></span>
                        <h3 class="text-[15px] font-semibold text-gray-900 dark:text-white">
                            {{ editingId ? $t("craftable-pro", "Edit Measure Unit") : $t("craftable-pro", "New Measure Unit") }}
                        </h3>
                    </div>
                    <button type="button" @click="modalOpen = false" class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-white/5"><XMarkIcon class="h-5 w-5" /></button>
                </header>
                <div class="space-y-5 p-6">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                        <TextInput v-model="form.name" name="name" label="Name" type="text" />
                        <TextInput v-model="form.symbol" name="symbol" label="Symbol" type="text" />
                    </div>
                    <TextArea v-model="form.description" name="description" label="Description" />
                </div>
                <footer class="flex justify-end gap-3 border-t border-gray-100 px-6 py-4 dark:border-[#2a2d38]">
                    <Button color="gray" variant="outline" @click="modalOpen = false">{{ $t("craftable-pro", "Cancel") }}</Button>
                    <Button :leftIcon="ArrowDownTrayIcon" @click="save" :loading="form.processing">{{ $t("craftable-pro", "Save") }}</Button>
                </footer>
            </div>
        </div>
    </Transition>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { useToast } from "@brackets/vue-toastification";
import {
    PlusIcon,
    TrashIcon,
    PencilSquareIcon,
    ScaleIcon,
    XMarkIcon,
    ArrowDownTrayIcon,
} from "@heroicons/vue/24/outline";
import {
    PageHeader,
    PageContent,
    Button,
    Listing,
    ListingHeaderCell,
    ListingDataCell,
    Modal,
    TextInput,
    TextArea,
} from "craftable-pro/Components";
import { PaginatedCollection } from "craftable-pro/types/pagination";
import type { MeasureUnit } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat)

interface Props {
    measureUnits: PaginatedCollection<MeasureUnit>;
}
defineProps<Props>();

const toast = useToast();
const modalOpen = ref(false);
const editingId = ref<number | null>(null);
const form = useForm({ name: "", symbol: "", description: "" });

function openCreate() {
    editingId.value = null;
    form.clearErrors();
    form.name = "";
    form.symbol = "";
    form.description = "";
    modalOpen.value = true;
}

function openEdit(item: MeasureUnit) {
    editingId.value = item.id;
    form.clearErrors();
    form.name = item.name ?? "";
    form.symbol = item.symbol ?? "";
    form.description = item.description ?? "";
    modalOpen.value = true;
}

function save() {
    const options = {
        preserveScroll: true,
        onSuccess: (page: any) => {
            modalOpen.value = false;
            const message = page?.props?.message;
            if (message) toast.success(message);
        },
    };

    if (editingId.value) {
        form.put(route("craftable-pro.measure-units.update", editingId.value), options);
    } else {
        form.post(route("craftable-pro.measure-units.store"), options);
    }
}
</script>
