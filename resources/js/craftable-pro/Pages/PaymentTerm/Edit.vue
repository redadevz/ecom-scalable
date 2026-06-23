<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Payment Term')"
        :subtitle="`Last updated at ${dayjs(
            paymentTerm.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.payment-terms.edit'"
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
import type { PaymentTerm, PaymentTermForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    paymentTerm: PaymentTerm;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<PaymentTermForm>(
    {
        sale_channel_id: props.paymentTerm?.sale_channel_id ?? "",
        delivery_type_id: props.paymentTerm?.delivery_type_id ?? "",
        payment_method_id: props.paymentTerm?.payment_method_id ?? "",
        payment_time_id: props.paymentTerm?.payment_time_id ?? "",
        is_allowed: props.paymentTerm?.is_allowed ?? false,
        is_active: props.paymentTerm?.is_active ?? false,
        comments: props.paymentTerm?.comments ?? ""
    },
    route("craftable-pro.payment-terms.update", [props.paymentTerm?.id])
);
</script>
