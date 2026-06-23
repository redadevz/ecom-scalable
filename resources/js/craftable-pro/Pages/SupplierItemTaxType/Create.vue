<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Create Supplier Item Tax Type')">
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.supplier-item-tax-types.create'"
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
import type { SupplierItemTaxTypeForm } from "./types";
import dayjs from 'dayjs'



interface Props {
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<SupplierItemTaxTypeForm>(
    {
        item_id: "",
        supplier_tax_type_id: "",
        start_time: "",
        end_time: "",
        description: ""
    },
    route("craftable-pro.supplier-item-tax-types.store"),
    "post"
);
</script>
