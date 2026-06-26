<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Order Line Discount')"
        :subtitle="`Last updated at ${dayjs(
            orderLineDiscount.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.order-line-discounts.edit'"
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
import type { OrderLineDiscount, OrderLineDiscountForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    orderLineDiscount: OrderLineDiscount;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<OrderLineDiscountForm>(
    {
        discount_id: props.orderLineDiscount?.discount_id ?? "",
        order_line_id: props.orderLineDiscount?.order_line_id ?? "",
        value: props.orderLineDiscount?.value ?? "",
        comments: props.orderLineDiscount?.comments ?? ""
    },
    route("craftable-pro.order-line-discounts.update", [props.orderLineDiscount?.id])
);
</script>
