<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Create Order Review')">
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.order-reviews.create'"
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
import type { OrderReviewForm } from "./types";
import dayjs from 'dayjs'



interface Props {
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<OrderReviewForm>(
    {
        order_id: "",
        customer_id: "",
        replied_by: "",
        rating: false,
        review: "",
        review_time: "",
        reply: "",
        reply_time: "",
        is_compensated: false,
        compensation_value: "",
        comments: ""
    },
    route("craftable-pro.order-reviews.store"),
    "post"
);
</script>
