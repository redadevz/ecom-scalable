<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Create Purchase')">
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.purchases.create'"
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
import type { PurchaseForm } from "./types";
import dayjs from 'dayjs'



interface Props {
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<PurchaseForm>(
    {
        store_id: "",
        supplier_id: "",
        entry_stock_time: "",
        description: "",
        is_paid: false,
        comments: ""
    },
    route("craftable-pro.purchases.store"),
    "post"
);
</script>
