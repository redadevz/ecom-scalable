<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Create Stock Return')">
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.stock-returns.create'"
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
import type { StockReturnForm } from "./types";
import dayjs from 'dayjs'



interface Props {
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<StockReturnForm>(
    {
        store_id: "",
        purchase_id: "",
        exit_stock_time: "",
        description: "",
        is_paid: false,
        comments: ""
    },
    route("craftable-pro.stock-returns.store"),
    "post"
);
</script>
