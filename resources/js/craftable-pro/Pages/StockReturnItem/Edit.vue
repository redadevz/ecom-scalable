<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Stock Return Item')"
        :subtitle="`Last updated at ${dayjs(
            stockReturnItem.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.stock-return-items.edit'"
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
import type { StockReturnItem, StockReturnItemForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    stockReturnItem: StockReturnItem;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<StockReturnItemForm>(
    {
        stock_return_id: props.stockReturnItem?.stock_return_id ?? "",
        item_id: props.stockReturnItem?.item_id ?? "",
        quantity: props.stockReturnItem?.quantity ?? "",
        supplier_price_before_tax: props.stockReturnItem?.supplier_price_before_tax ?? "",
        supplier_tax_value: props.stockReturnItem?.supplier_tax_value ?? "",
        supplier_price_after_tax: props.stockReturnItem?.supplier_price_after_tax ?? "",
        supplier_discount_value: props.stockReturnItem?.supplier_discount_value ?? "",
        return_amount: props.stockReturnItem?.return_amount ?? "",
        description: props.stockReturnItem?.description ?? "",
        comments: props.stockReturnItem?.comments ?? ""
    },
    route("craftable-pro.stock-return-items.update", [props.stockReturnItem?.id])
);
</script>
