<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Create Payment')">
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.payments.create'"
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
import type { PaymentForm } from "./types";
import dayjs from 'dayjs'



interface Props {
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<PaymentForm>(
    {
        invoice_id: "",
        payment_method_id: "",
        payment_no: "",
        amount: "",
        cash_paid: "",
        cash_change: "",
        payment_time: "",
        comments: ""
    },
    route("craftable-pro.payments.store"),
    "post"
);
</script>
