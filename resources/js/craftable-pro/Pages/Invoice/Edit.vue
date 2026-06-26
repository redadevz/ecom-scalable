<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Invoice')"
        :subtitle="`Last updated at ${dayjs(
            invoice.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.invoices.edit'"
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
import type { Invoice, InvoiceForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    invoice: Invoice;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<InvoiceForm>(
    {
        order_id: props.invoice?.order_id ?? "",
        invoice_no: props.invoice?.invoice_no ?? "",
        is_paid: props.invoice?.is_paid ?? false,
        payment_time: props.invoice?.payment_time ?? "",
        comments: props.invoice?.comments ?? ""
    },
    route("craftable-pro.invoices.update", [props.invoice?.id])
);
</script>
