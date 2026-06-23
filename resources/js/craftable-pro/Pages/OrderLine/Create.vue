<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Create Order Line')">
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.order-lines.create'"
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
import type { OrderLineForm } from "./types";
import dayjs from 'dayjs'



interface Props {
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<OrderLineForm>(
    {
        store_id: "",
        order_id: "",
        item_id: "",
        line_no: "",
        description: "",
        customer_notes: "",
        quantity: "",
        current_item_cost: "",
        markup_percentage: "",
        price_before_tax: "",
        tax_value: "",
        price_after_tax: "",
        price_before_discount: "",
        discount_value: "",
        price_after_discount: "",
        price_adjustment: "",
        price_adjustment_reason: "",
        price: "",
        is_canceled: false,
        canceled_time: "",
        cancel_reason: "",
        return_required: false,
        return_quantity: "",
        return_time: "",
        customer_review: "",
        customer_like: false,
        comments: ""
    },
    route("craftable-pro.order-lines.store"),
    "post"
);
</script>
