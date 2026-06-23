<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Supplier Item Tax Type')"
        :subtitle="`Last updated at ${dayjs(
            supplierItemTaxType.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.supplier-item-tax-types.edit'"
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
import type { SupplierItemTaxType, SupplierItemTaxTypeForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    supplierItemTaxType: SupplierItemTaxType;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<SupplierItemTaxTypeForm>(
    {
        item_id: props.supplierItemTaxType?.item_id ?? "",
        supplier_tax_type_id: props.supplierItemTaxType?.supplier_tax_type_id ?? "",
        start_time: props.supplierItemTaxType?.start_time ?? "",
        end_time: props.supplierItemTaxType?.end_time ?? "",
        description: props.supplierItemTaxType?.description ?? ""
    },
    route("craftable-pro.supplier-item-tax-types.update", [props.supplierItemTaxType?.id])
);
</script>
