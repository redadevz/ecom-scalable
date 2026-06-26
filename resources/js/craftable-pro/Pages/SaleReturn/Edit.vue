<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Sale Return')"
        :subtitle="`Last updated at ${dayjs(
            saleReturn.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.sale-returns.edit'"
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
import type { SaleReturn, SaleReturnForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    saleReturn: SaleReturn;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<SaleReturnForm>(
    {
        store_id: props.saleReturn?.store_id ?? "",
        order_id: props.saleReturn?.order_id ?? "",
        entry_stock_time: props.saleReturn?.entry_stock_time ?? "",
        is_refund_required: props.saleReturn?.is_refund_required ?? false,
        refund_amount: props.saleReturn?.refund_amount ?? "",
        is_refunded: props.saleReturn?.is_refunded ?? false,
        refund_time: props.saleReturn?.refund_time ?? "",
        description: props.saleReturn?.description ?? "",
        comments: props.saleReturn?.comments ?? ""
    },
    route("craftable-pro.sale-returns.update", [props.saleReturn?.id])
);
</script>
