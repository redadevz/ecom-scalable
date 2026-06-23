<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Order Line')"
        :subtitle="`Last updated at ${dayjs(
            orderLine.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.order-lines.edit'"
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
import type { OrderLine, OrderLineForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    orderLine: OrderLine;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<OrderLineForm>(
    {
        store_id: props.orderLine?.store_id ?? "",
        order_id: props.orderLine?.order_id ?? "",
        item_id: props.orderLine?.item_id ?? "",
        line_no: props.orderLine?.line_no ?? "",
        description: props.orderLine?.description ?? "",
        customer_notes: props.orderLine?.customer_notes ?? "",
        quantity: props.orderLine?.quantity ?? "",
        current_item_cost: props.orderLine?.current_item_cost ?? "",
        markup_percentage: props.orderLine?.markup_percentage ?? "",
        price_before_tax: props.orderLine?.price_before_tax ?? "",
        tax_value: props.orderLine?.tax_value ?? "",
        price_after_tax: props.orderLine?.price_after_tax ?? "",
        price_before_discount: props.orderLine?.price_before_discount ?? "",
        discount_value: props.orderLine?.discount_value ?? "",
        price_after_discount: props.orderLine?.price_after_discount ?? "",
        price_adjustment: props.orderLine?.price_adjustment ?? "",
        price_adjustment_reason: props.orderLine?.price_adjustment_reason ?? "",
        price: props.orderLine?.price ?? "",
        is_canceled: props.orderLine?.is_canceled ?? false,
        canceled_time: props.orderLine?.canceled_time ?? "",
        cancel_reason: props.orderLine?.cancel_reason ?? "",
        return_required: props.orderLine?.return_required ?? false,
        return_quantity: props.orderLine?.return_quantity ?? "",
        return_time: props.orderLine?.return_time ?? "",
        customer_review: props.orderLine?.customer_review ?? "",
        customer_like: props.orderLine?.customer_like ?? false,
        comments: props.orderLine?.comments ?? ""
    },
    route("craftable-pro.order-lines.update", [props.orderLine?.id])
);
</script>
