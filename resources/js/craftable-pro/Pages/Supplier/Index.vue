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
                <ListingHeaderCell sortBy='created_by'>
                    {{ $t("craftable-pro", "Created By") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='code'>
                    {{ $t("craftable-pro", "Code") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='phone'>
                    {{ $t("craftable-pro", "Phone") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='first_name'>
                    {{ $t("craftable-pro", "First Name") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='last_name'>
                    {{ $t("craftable-pro", "Last Name") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='is_company'>
                    {{ $t("craftable-pro", "Is Company") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='company_name'>
                    {{ $t("craftable-pro", "Company Name") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='tax_number'>
                    {{ $t("craftable-pro", "Tax Number") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='is_tax_exempted'>
                    {{ $t("craftable-pro", "Is Tax Exempted") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='billing_address'>
                    {{ $t("craftable-pro", "Billing Address") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='postal_code'>
                    {{ $t("craftable-pro", "Postal Code") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='email'>
                    {{ $t("craftable-pro", "Email") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='is_active'>
                    {{ $t("craftable-pro", "Is Active") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='comments'>
                    {{ $t("craftable-pro", "Comments") }}
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
                    <span class="font-medium text-gray-900 dark:text-white">{{ item.created_by }}</span>
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.code }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.phone }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.first_name }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.last_name }}
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="is_company"
                        v-model="item.is_company"
                        :updateUrl="route('craftable-pro.suppliers.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.company_name }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.tax_number }}
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="is_tax_exempted"
                        v-model="item.is_tax_exempted"
                        :updateUrl="route('craftable-pro.suppliers.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.billing_address }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.postal_code }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.email }}
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="is_active"
                        v-model="item.is_active"
                        :updateUrl="route('craftable-pro.suppliers.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.comments }}
                </ListingDataCell>
                <ListingDataCell>
                    <span class="text-sm text-gray-500">{{ item.created_at && dayjs(item.created_at).format('DD MMM YYYY') }}</span>
                </ListingDataCell>
                <ListingDataCell>
                    <div class="flex items-center justify-end gap-3">
                        <IconButton
                            :as="Link"
                            :href="route('craftable-pro.suppliers.edit', item)"
                            variant="ghost"
                            color="gray"
                            :icon="PencilSquareIcon"
                            v-can="'craftable-pro.suppliers.edit'"
                        />

                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <IconButton
                                    @click="() => setIsOpen(true)"
                                    color="gray"
                                    variant="ghost"
                                    :icon="TrashIcon"
                                    v-can="'craftable-pro.suppliers.destroy'"
                                />
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
                                            action('delete', route('craftable-pro.suppliers.destroy', item), {
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
import type { Supplier } from "./types";
import type { PageProps } from "craftable-pro/types/page";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat)



interface Props {
    suppliers: PaginatedCollection<Supplier>;
}
defineProps<Props>();

</script>
