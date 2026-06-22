<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Order Item')"
        :subtitle="`Last updated at ${dayjs(
            orderItem.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.order-items.edit'"
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
import type { OrderItem, OrderItemForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    orderItem: OrderItem;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<OrderItemForm>(
    {
        order_id: props.orderItem?.order_id ?? "",
        product_id: props.orderItem?.product_id ?? "",
        product_name: props.orderItem?.product_name ?? "",
        quantity: props.orderItem?.quantity ?? "",
        unit_price: props.orderItem?.unit_price ?? ""
    },
    route("craftable-pro.order-items.update", [props.orderItem?.id])
);
</script>
