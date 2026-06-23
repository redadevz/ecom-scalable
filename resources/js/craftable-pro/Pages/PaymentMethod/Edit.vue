<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Payment Method')"
        :subtitle="`Last updated at ${dayjs(
            paymentMethod.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.payment-methods.edit'"
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
import type { PaymentMethod, PaymentMethodForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    paymentMethod: PaymentMethod;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<PaymentMethodForm>(
    {
        name: props.paymentMethod?.name ?? "",
        code: props.paymentMethod?.code ?? "",
        sequence_no: props.paymentMethod?.sequence_no ?? "",
        is_active: props.paymentMethod?.is_active ?? false,
        is_customer_required: props.paymentMethod?.is_customer_required ?? false,
        description: props.paymentMethod?.description ?? ""
    },
    route("craftable-pro.payment-methods.update", [props.paymentMethod?.id])
);
</script>
