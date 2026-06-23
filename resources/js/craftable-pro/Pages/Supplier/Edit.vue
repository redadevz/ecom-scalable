<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Supplier')"
        :subtitle="`Last updated at ${dayjs(
            supplier.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.suppliers.edit'"
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
import type { Supplier, SupplierForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    supplier: Supplier;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<SupplierForm>(
    {
        store_id: props.supplier?.store_id ?? "",
        city_id: props.supplier?.city_id ?? "",
        created_by: props.supplier?.created_by ?? "",
        code: props.supplier?.code ?? "",
        phone: props.supplier?.phone ?? "",
        first_name: props.supplier?.first_name ?? "",
        last_name: props.supplier?.last_name ?? "",
        is_company: props.supplier?.is_company ?? false,
        company_name: props.supplier?.company_name ?? "",
        tax_number: props.supplier?.tax_number ?? "",
        is_tax_exempted: props.supplier?.is_tax_exempted ?? false,
        billing_address: props.supplier?.billing_address ?? "",
        postal_code: props.supplier?.postal_code ?? "",
        email: props.supplier?.email ?? "",
        is_active: props.supplier?.is_active ?? false,
        comments: props.supplier?.comments ?? ""
    },
    route("craftable-pro.suppliers.update", [props.supplier?.id])
);
</script>
