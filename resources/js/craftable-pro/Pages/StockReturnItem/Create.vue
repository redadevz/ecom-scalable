<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Create Stock Return Item')">
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.stock-return-items.create'"
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
import type { StockReturnItemForm } from "./types";
import dayjs from 'dayjs'



interface Props {
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<StockReturnItemForm>(
    {
        stock_return_id: "",
        item_id: "",
        quantity: "",
        supplier_price_before_tax: "",
        supplier_tax_value: "",
        supplier_price_after_tax: "",
        supplier_discount_value: "",
        return_amount: "",
        description: "",
        comments: ""
    },
    route("craftable-pro.stock-return-items.store"),
    "post"
);
</script>
