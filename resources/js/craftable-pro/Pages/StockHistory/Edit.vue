<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Stock History')"
        :subtitle="`Last updated at ${dayjs(
            stockHistory.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.stock-histories.edit'"
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
import type { StockHistory, StockHistoryForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    stockHistory: StockHistory;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<StockHistoryForm>(
    {
        store_id: props.stockHistory?.store_id ?? "",
        item_id: props.stockHistory?.item_id ?? "",
        document_id: props.stockHistory?.document_id ?? "",
        initial_stock_quantity: props.stockHistory?.initial_stock_quantity ?? "",
        initial_item_cost: props.stockHistory?.initial_item_cost ?? "",
        is_stock_entry: props.stockHistory?.is_stock_entry ?? false,
        quantity: props.stockHistory?.quantity ?? "",
        current_stock_quantity: props.stockHistory?.current_stock_quantity ?? "",
        current_item_cost: props.stockHistory?.current_item_cost ?? "",
        description: props.stockHistory?.description ?? "",
        comments: props.stockHistory?.comments ?? ""
    },
    route("craftable-pro.stock-histories.update", [props.stockHistory?.id])
);
</script>
