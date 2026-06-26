<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Create Refund')">
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.refunds.create'"
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
import type { RefundForm } from "./types";
import dayjs from 'dayjs'



interface Props {
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<RefundForm>(
    {
        sale_return_id: "",
        payment_method_id: "",
        refund_no: "",
        amount: "",
        cash_paid: "",
        cash_change: "",
        refund_time: "",
        comments: ""
    },
    route("craftable-pro.refunds.store"),
    "post"
);
</script>
