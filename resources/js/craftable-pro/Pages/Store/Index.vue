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
                <ListingHeaderCell sortBy='code'>
                    {{ $t("craftable-pro", "Code") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='name'>
                    {{ $t("craftable-pro", "Name") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='is_active'>
                    {{ $t("craftable-pro", "Is Active") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='legal_entity_name'>
                    {{ $t("craftable-pro", "Legal Entity Name") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='tax_code'>
                    {{ $t("craftable-pro", "Tax Code") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='address'>
                    {{ $t("craftable-pro", "Address") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='registration_number'>
                    {{ $t("craftable-pro", "Registration Number") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='phone'>
                    {{ $t("craftable-pro", "Phone") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='fax'>
                    {{ $t("craftable-pro", "Fax") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='email'>
                    {{ $t("craftable-pro", "Email") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='logo'>
                    {{ $t("craftable-pro", "Logo") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='bank_branch'>
                    {{ $t("craftable-pro", "Bank Branch") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='bank_code'>
                    {{ $t("craftable-pro", "Bank Code") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='bank_account'>
                    {{ $t("craftable-pro", "Bank Account") }}
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
                    <span class="font-medium text-gray-900 dark:text-white">{{ item.code }}</span>
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.name }}
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="is_active"
                        v-model="item.is_active"
                        :updateUrl="route('craftable-pro.stores.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.legal_entity_name }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.tax_code }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.address }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.registration_number }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.phone }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.fax }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.email }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.logo }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.bank_branch }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.bank_code }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.bank_account }}
                </ListingDataCell>
                <ListingDataCell>
                    <span class="text-sm text-gray-500">{{ item.created_at && dayjs(item.created_at).format('DD MMM YYYY') }}</span>
                </ListingDataCell>
                <ListingDataCell>
                    <div class="flex items-center justify-end gap-3">
                        <IconButton
                            :as="Link"
                            :href="route('craftable-pro.stores.edit', item)"
                            variant="ghost"
                            color="gray"
                            :icon="PencilSquareIcon"
                            v-can="'craftable-pro.stores.edit'"
                        />

                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <IconButton
                                    @click="() => setIsOpen(true)"
                                    color="gray"
                                    variant="ghost"
                                    :icon="TrashIcon"
                                    v-can="'craftable-pro.stores.destroy'"
                                />
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
                                            action('delete', route('craftable-pro.stores.destroy', item), {
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
import type { Store } from "./types";
import type { PageProps } from "craftable-pro/types/page";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat)



interface Props {
    stores: PaginatedCollection<Store>;
}
defineProps<Props>();

</script>
