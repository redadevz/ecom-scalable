<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Order Review')"
        :subtitle="`Last updated at ${dayjs(
            orderReview.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.order-reviews.edit'"
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
import type { OrderReview, OrderReviewForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    orderReview: OrderReview;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<OrderReviewForm>(
    {
        order_id: props.orderReview?.order_id ?? "",
        customer_id: props.orderReview?.customer_id ?? "",
        replied_by: props.orderReview?.replied_by ?? "",
        rating: props.orderReview?.rating ?? false,
        review: props.orderReview?.review ?? "",
        review_time: props.orderReview?.review_time ?? "",
        reply: props.orderReview?.reply ?? "",
        reply_time: props.orderReview?.reply_time ?? "",
        is_compensated: props.orderReview?.is_compensated ?? false,
        compensation_value: props.orderReview?.compensation_value ?? "",
        comments: props.orderReview?.comments ?? ""
    },
    route("craftable-pro.order-reviews.update", [props.orderReview?.id])
);
</script>
