<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Create Discount Type')">
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.discount-types.create'"
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
import type { DiscountTypeForm } from "./types";
import dayjs from 'dayjs'



interface Props {
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<DiscountTypeForm>(
    {
        store_id: "",
        loyalty_card_type_id: "",
        name: "",
        description: "",
        is_percentage: false,
        value: "",
        coupon_code: "",
        min_order_value: "",
        min_item_quantity: "",
        apply_to_all: false,
        apply_to_next: false,
        max_discount_value: "",
        start_time: "",
        end_time: "",
        is_active: false,
        comments: ""
    },
    route("craftable-pro.discount-types.store"),
    "post"
);
</script>
