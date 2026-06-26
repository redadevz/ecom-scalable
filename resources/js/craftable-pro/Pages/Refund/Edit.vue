<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Refund')"
        :subtitle="`Last updated at ${dayjs(
            refund.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.refunds.edit'"
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
import type { Refund, RefundForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    refund: Refund;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<RefundForm>(
    {
        sale_return_id: props.refund?.sale_return_id ?? "",
        payment_method_id: props.refund?.payment_method_id ?? "",
        refund_no: props.refund?.refund_no ?? "",
        amount: props.refund?.amount ?? "",
        cash_paid: props.refund?.cash_paid ?? "",
        cash_change: props.refund?.cash_change ?? "",
        refund_time: props.refund?.refund_time ?? "",
        comments: props.refund?.comments ?? ""
    },
    route("craftable-pro.refunds.update", [props.refund?.id])
);
</script>
