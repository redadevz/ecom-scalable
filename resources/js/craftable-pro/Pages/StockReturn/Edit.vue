<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Stock Return')"
        :subtitle="`Last updated at ${dayjs(
            stockReturn.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.stock-returns.edit'"
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
import type { StockReturn, StockReturnForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    stockReturn: StockReturn;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<StockReturnForm>(
    {
        store_id: props.stockReturn?.store_id ?? "",
        purchase_id: props.stockReturn?.purchase_id ?? "",
        exit_stock_time: props.stockReturn?.exit_stock_time ?? "",
        description: props.stockReturn?.description ?? "",
        is_paid: props.stockReturn?.is_paid ?? false,
        comments: props.stockReturn?.comments ?? ""
    },
    route("craftable-pro.stock-returns.update", [props.stockReturn?.id])
);
</script>
