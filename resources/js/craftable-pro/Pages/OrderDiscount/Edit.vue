<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Order Discount')"
        :subtitle="`Last updated at ${dayjs(
            orderDiscount.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.order-discounts.edit'"
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
import type { OrderDiscount, OrderDiscountForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    orderDiscount: OrderDiscount;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<OrderDiscountForm>(
    {
        discount_id: props.orderDiscount?.discount_id ?? "",
        order_id: props.orderDiscount?.order_id ?? "",
        value: props.orderDiscount?.value ?? "",
        comments: props.orderDiscount?.comments ?? ""
    },
    route("craftable-pro.order-discounts.update", [props.orderDiscount?.id])
);
</script>
