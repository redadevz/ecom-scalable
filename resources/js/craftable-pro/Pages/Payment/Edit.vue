<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Payment')"
        :subtitle="`Last updated at ${dayjs(
            payment.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.payments.edit'"
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
import type { Payment, PaymentForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    payment: Payment;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<PaymentForm>(
    {
        invoice_id: props.payment?.invoice_id ?? "",
        payment_method_id: props.payment?.payment_method_id ?? "",
        payment_no: props.payment?.payment_no ?? "",
        amount: props.payment?.amount ?? "",
        cash_paid: props.payment?.cash_paid ?? "",
        cash_change: props.payment?.cash_change ?? "",
        payment_time: props.payment?.payment_time ?? "",
        comments: props.payment?.comments ?? ""
    },
    route("craftable-pro.payments.update", [props.payment?.id])
);
</script>
