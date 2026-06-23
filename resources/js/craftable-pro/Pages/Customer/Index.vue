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
                        <Button
                            @click="() => setIsOpen(true)"
                            color="gray"
                            variant="outline"
                            size="sm"
                            :leftIcon="TrashIcon"
                            v-can="'craftable-pro.customers.destroy'"
                        >
                            {{ $t("craftable-pro", "Delete") }}
                        </Button>
                    </template>

                    <template #title>
                        {{ $t("craftable-pro", "Delete Customer") }}
                    </template>

                    <template #content>
                        {{
                            $t(
                                "craftable-pro",
                                "Are you sure you want to delete selected Customer? All data will be permanently removed from our servers forever. This action cannot be undone."
                            )
                        }}
                    </template>

                    <template #buttons="{ setIsOpen }">
                        <Button
                            @click.prevent="
                                () => {
                                    bulkAction('post', route('craftable-pro.customers.bulk-destroy'), {
                                        onFinish: () => setIsOpen(false),
                                    });
                                }
                            "
                            color="danger"
                            v-can="'craftable-pro.customers.destroy'"
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
                <ListingHeaderCell sortBy='id'>
                    {{ $t("craftable-pro", "Id") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='city_id'>
                    {{ $t("craftable-pro", "City Id") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='created_at_store_id'>
                    {{ $t("craftable-pro", "Created At Store Id") }}
                </ListingHeaderCell>
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
                <ListingHeaderCell sortBy='is_registered_online'>
                    {{ $t("craftable-pro", "Is Registered Online") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='email'>
                    {{ $t("craftable-pro", "Email") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='username'>
                    {{ $t("craftable-pro", "Username") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='credit'>
                    {{ $t("craftable-pro", "Credit") }}
                </ListingHeaderCell>
                <ListingHeaderCell sortBy='last_login_time'>
                    {{ $t("craftable-pro", "Last Login Time") }}
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
                     {{ item.id }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.city_id }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.created_at_store_id }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.created_by }}
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
                        :updateUrl="route('craftable-pro.customers.update', item.id)"
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
                        :updateUrl="route('craftable-pro.customers.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.billing_address }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.postal_code }}
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="is_registered_online"
                        v-model="item.is_registered_online"
                        :updateUrl="route('craftable-pro.customers.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.email }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.username }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.credit }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.last_login_time && dayjs(item.last_login_time).format('DD.MM.YYYY HH:mm') }}
                </ListingDataCell>
                <ListingDataCell>
                    <ListingToggle
                        name="is_active"
                        v-model="item.is_active"
                        :updateUrl="route('craftable-pro.customers.update', item.id)"
                    />
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.comments }}
                </ListingDataCell>
                <ListingDataCell>
                     {{ item.created_at && dayjs(item.created_at).format('DD.MM.YYYY HH:mm') }}
                </ListingDataCell>
                <ListingDataCell>
                    <div class="flex items-center justify-end gap-3">
                        <IconButton
                            :as="Link"
                            :href="route('craftable-pro.customers.edit', item)"
                            variant="ghost"
                            color="gray"
                            :icon="PencilSquareIcon"
                            v-can="'craftable-pro.customers.edit'"
                        />

                        <Modal type="danger">
                            <template #trigger="{ setIsOpen }">
                                <IconButton
                                    @click="() => setIsOpen(true)"
                                    color="gray"
                                    variant="ghost"
                                    :icon="TrashIcon"
                                    v-can="'craftable-pro.customers.destroy'"
                                />
                            </template>

                            <template #title>
                                {{ $t("craftable-pro", "Delete Customer") }}
                            </template>

                            <template #content>
                                {{
                                    $t(
                                        "craftable-pro",
                                        "Are you sure you want to delete selected Customer? All data will be permanently removed from our servers forever. This action cannot be undone."
                                    )
                                }}
                            </template>

                            <template #buttons="{ setIsOpen }">
                                <Button
                                    @click.prevent="
                                        () => {
                                            action('delete', route('craftable-pro.customers.destroy', item), {
                                                onFinish: () => setIsOpen(false),
                                            });
                                        }
                                    "
                                    color="danger"
                                    v-can="'craftable-pro.customers.destroy'"
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
import type { Customer } from "./types";
import type { PageProps } from "craftable-pro/types/page";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat)



interface Props {
    customers: PaginatedCollection<Customer>;
}
defineProps<Props>();

</script>
