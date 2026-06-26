<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Invoice Line')"
        :subtitle="`Last updated at ${dayjs(
            invoiceLine.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.invoice-lines.edit'"
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
import type { InvoiceLine, InvoiceLineForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    invoiceLine: InvoiceLine;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<InvoiceLineForm>(
    {
        invoice_id: props.invoiceLine?.invoice_id ?? "",
        order_line_id: props.invoiceLine?.order_line_id ?? "",
        line_no: props.invoiceLine?.line_no ?? "",
        comments: props.invoiceLine?.comments ?? ""
    },
    route("craftable-pro.invoice-lines.update", [props.invoiceLine?.id])
);
</script>
