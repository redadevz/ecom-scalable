<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Create Stock History')">
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.stock-histories.create'"
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
import type { StockHistoryForm } from "./types";
import dayjs from 'dayjs'



interface Props {
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<StockHistoryForm>(
    {
        store_id: "",
        item_id: "",
        document_id: "",
        initial_stock_quantity: "",
        initial_item_cost: "",
        is_stock_entry: false,
        quantity: "",
        current_stock_quantity: "",
        current_item_cost: "",
        description: "",
        comments: ""
    },
    route("craftable-pro.stock-histories.store"),
    "post"
);
</script>
