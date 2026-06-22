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
        order_id: props.payment?.order_id ?? "",
        provider: props.payment?.provider ?? "",
        provider_reference: props.payment?.provider_reference ?? "",
        status: props.payment?.status ?? "",
        amount: props.payment?.amount ?? "",
        currency: props.payment?.currency ?? ""
    },
    route("craftable-pro.payments.update", [props.payment?.id])
);
</script>
