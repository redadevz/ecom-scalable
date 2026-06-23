<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Supplier Tax Type')"
        :subtitle="`Last updated at ${dayjs(
            supplierTaxType.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.supplier-tax-types.edit'"
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
import type { SupplierTaxType, SupplierTaxTypeForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    supplierTaxType: SupplierTaxType;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<SupplierTaxTypeForm>(
    {
        supplier_id: props.supplierTaxType?.supplier_id ?? "",
        name: props.supplierTaxType?.name ?? "",
        code: props.supplierTaxType?.code ?? "",
        description: props.supplierTaxType?.description ?? "",
        is_percentage: props.supplierTaxType?.is_percentage ?? false,
        value: props.supplierTaxType?.value ?? "",
        start_time: props.supplierTaxType?.start_time ?? "",
        end_time: props.supplierTaxType?.end_time ?? "",
        is_active: props.supplierTaxType?.is_active ?? false,
        comments: props.supplierTaxType?.comments ?? ""
    },
    route("craftable-pro.supplier-tax-types.update", [props.supplierTaxType?.id])
);
</script>
