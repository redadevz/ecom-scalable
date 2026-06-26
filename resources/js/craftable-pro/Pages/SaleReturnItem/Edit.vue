<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Sale Return Item')"
        :subtitle="`Last updated at ${dayjs(
            saleReturnItem.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.sale-return-items.edit'"
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
import type { SaleReturnItem, SaleReturnItemForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    saleReturnItem: SaleReturnItem;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<SaleReturnItemForm>(
    {
        sale_return_id: props.saleReturnItem?.sale_return_id ?? "",
        order_line_id: props.saleReturnItem?.order_line_id ?? "",
        item_id: props.saleReturnItem?.item_id ?? "",
        line_no: props.saleReturnItem?.line_no ?? "",
        quantity: props.saleReturnItem?.quantity ?? "",
        return_quantity: props.saleReturnItem?.return_quantity ?? "",
        description: props.saleReturnItem?.description ?? "",
        comments: props.saleReturnItem?.comments ?? ""
    },
    route("craftable-pro.sale-return-items.update", [props.saleReturnItem?.id])
);
</script>
