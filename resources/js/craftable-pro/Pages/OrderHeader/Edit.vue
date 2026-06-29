<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Order Header')"
        :subtitle="`Last updated at ${dayjs(
            orderHeader.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <div class="flex items-center gap-3">
            <Button
                :leftIcon="ArrowDownTrayIcon"
                @click="submit"
                :loading="form.processing"
                variant="outline"
                color="gray"
                v-can="'craftable-pro.order-headers.edit'"
            >
                {{ $t("craftable-pro", "Save") }}
            </Button>
            <Button
                :leftIcon="CheckCircleIcon"
                @click="confirmOrder"
                :loading="confirming"
                v-can="'craftable-pro.order-headers.edit'"
            >
                {{ $t("craftable-pro", "Confirm Order") }}
            </Button>
        </div>
    </PageHeader>

    <Form :form="form" :submit="submit"  />
</template>

<script setup lang="ts">
import { ref } from "vue";
import { ArrowDownTrayIcon, CheckCircleIcon } from "@heroicons/vue/24/outline";
import { router } from "@inertiajs/vue3";
import { PageHeader, Button } from "craftable-pro/Components";
import { useForm } from "craftable-pro/hooks/useForm";
import Form from "./Form.vue";
import type { OrderHeader, OrderHeaderForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    orderHeader: OrderHeader;

}

const props = defineProps<Props>();

const confirming = ref(false);

const confirmOrder = () => {
    router.post(
        route("craftable-pro.order-headers.confirm", props.orderHeader.id),
        {},
        {
            preserveScroll: true,
            onStart: () => (confirming.value = true),
            onFinish: () => (confirming.value = false),
        }
    );
};

const { form, submit } = useForm<OrderHeaderForm>(
    {
        store_id: props.orderHeader?.store_id ?? "",
        sale_channel_id: props.orderHeader?.sale_channel_id ?? "",
        delivery_type_id: props.orderHeader?.delivery_type_id ?? "",
        payment_method_id: props.orderHeader?.payment_method_id ?? "",
        payment_time_id: props.orderHeader?.payment_time_id ?? "",
        customer_id: props.orderHeader?.customer_id ?? "",
        loyalty_card_id: props.orderHeader?.loyalty_card_id ?? "",
        created_by: props.orderHeader?.created_by ?? "",
        approved_by: props.orderHeader?.approved_by ?? "",
        managed_by: props.orderHeader?.managed_by ?? "",
        order_no: props.orderHeader?.order_no ?? "",
        customer_notes: props.orderHeader?.customer_notes ?? "",
        price_before_tax: props.orderHeader?.price_before_tax ?? "",
        total_tax_value: props.orderHeader?.total_tax_value ?? "",
        price_after_tax: props.orderHeader?.price_after_tax ?? "",
        price_before_discount: props.orderHeader?.price_before_discount ?? "",
        order_items_discount: props.orderHeader?.order_items_discount ?? "",
        order_discount: props.orderHeader?.order_discount ?? "",
        total_discount_value: props.orderHeader?.total_discount_value ?? "",
        price_after_discount: props.orderHeader?.price_after_discount ?? "",
        price_adjustment: props.orderHeader?.price_adjustment ?? "",
        price_adjustment_reason: props.orderHeader?.price_adjustment_reason ?? "",
        price: props.orderHeader?.price ?? "",
        latest_status: props.orderHeader?.latest_status ?? "",
        latest_status_update: props.orderHeader?.latest_status_update ?? "",
        is_submitted: props.orderHeader?.is_submitted ?? false,
        submitted_time: props.orderHeader?.submitted_time ?? "",
        is_approved: props.orderHeader?.is_approved ?? false,
        approved_time: props.orderHeader?.approved_time ?? "",
        is_canceled: props.orderHeader?.is_canceled ?? false,
        canceled_time: props.orderHeader?.canceled_time ?? "",
        cancel_reason: props.orderHeader?.cancel_reason ?? "",
        is_scheduled: props.orderHeader?.is_scheduled ?? false,
        scheduled_time: props.orderHeader?.scheduled_time ?? "",
        is_ready: props.orderHeader?.is_ready ?? false,
        ready_time: props.orderHeader?.ready_time ?? "",
        is_delivered: props.orderHeader?.is_delivered ?? false,
        delivered_time: props.orderHeader?.delivered_time ?? "",
        is_paid: props.orderHeader?.is_paid ?? false,
        payment_time: props.orderHeader?.payment_time ?? "",
        is_completed: props.orderHeader?.is_completed ?? false,
        completed_time: props.orderHeader?.completed_time ?? "",
        return_required: props.orderHeader?.return_required ?? false,
        return_time: props.orderHeader?.return_time ?? "",
        comments: props.orderHeader?.comments ?? ""
    },
    route("craftable-pro.order-headers.update", [props.orderHeader?.id])
);
</script>
