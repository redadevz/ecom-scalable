<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Create Price')">
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.prices.create'"
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
import type { PriceForm } from "./types";
import dayjs from 'dayjs'



interface Props {
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<PriceForm>(
    {
        item_id: "",
        store_id: "",
        created_by: "",
        description: "",
        current_item_cost: "",
        markup_percentage: "",
        price_before_tax: "",
        tax_value: "",
        price_after_tax: "",
        sale_price: "",
        price_change_allowed: false,
        start_time: "",
        end_time: "",
        is_active: false,
        comments: ""
    },
    route("craftable-pro.prices.store"),
    "post"
);
</script>
