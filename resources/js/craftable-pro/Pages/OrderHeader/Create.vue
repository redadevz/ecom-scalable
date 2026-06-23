<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Create Order Header')">
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.order-headers.create'"
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
import type { OrderHeaderForm } from "./types";
import dayjs from 'dayjs'



interface Props {
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<OrderHeaderForm>(
    {
        store_id: "",
        sale_channel_id: "",
        delivery_type_id: "",
        payment_method_id: "",
        payment_time_id: "",
        customer_id: "",
        loyalty_card_id: "",
        created_by: "",
        approved_by: "",
        managed_by: "",
        order_no: "",
        customer_notes: "",
        price_before_tax: "",
        total_tax_value: "",
        price_after_tax: "",
        price_before_discount: "",
        order_items_discount: "",
        order_discount: "",
        total_discount_value: "",
        price_after_discount: "",
        price_adjustment: "",
        price_adjustment_reason: "",
        price: "",
        latest_status: "",
        latest_status_update: "",
        is_submitted: false,
        submitted_time: "",
        is_approved: false,
        approved_time: "",
        is_canceled: false,
        canceled_time: "",
        cancel_reason: "",
        is_scheduled: false,
        scheduled_time: "",
        is_ready: false,
        ready_time: "",
        is_delivered: false,
        delivered_time: "",
        is_paid: false,
        payment_time: "",
        is_completed: false,
        completed_time: "",
        return_required: false,
        return_time: "",
        comments: ""
    },
    route("craftable-pro.order-headers.store"),
    "post"
);
</script>
