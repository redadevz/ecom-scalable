<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Customer')"
        :subtitle="`Last updated at ${dayjs(
            customer.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.customers.edit'"
        >
            {{ $t("craftable-pro", "Save") }}
        </Button>
    </PageHeader>

    <Form :form="form" :submit="submit"  />
</template>

<script setup lang="ts">
import { ArrowDownTrayIcon } from "@heroicons/vue/24/outline";
import { PageHeader, Button } from "craftable-pro/Components";
import { useForm } from "craftable-pro/hooks/useForm";
import Form from "./Form.vue";
import type { Customer, CustomerForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    customer: Customer;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<CustomerForm>(
    {
        city_id: props.customer?.city_id ?? "",
        created_at_store_id: props.customer?.created_at_store_id ?? "",
        created_by: props.customer?.created_by ?? "",
        code: props.customer?.code ?? "",
        phone: props.customer?.phone ?? "",
        first_name: props.customer?.first_name ?? "",
        last_name: props.customer?.last_name ?? "",
        is_company: props.customer?.is_company ?? false,
        company_name: props.customer?.company_name ?? "",
        tax_number: props.customer?.tax_number ?? "",
        is_tax_exempted: props.customer?.is_tax_exempted ?? false,
        billing_address: props.customer?.billing_address ?? "",
        postal_code: props.customer?.postal_code ?? "",
        is_registered_online: props.customer?.is_registered_online ?? false,
        email: props.customer?.email ?? "",
        username: props.customer?.username ?? "",
        password: props.customer?.password ?? "",
        credit: props.customer?.credit ?? "",
        last_login_time: props.customer?.last_login_time ?? "",
        is_active: props.customer?.is_active ?? false,
        comments: props.customer?.comments ?? ""
    },
    route("craftable-pro.customers.update", [props.customer?.id])
);
</script>
