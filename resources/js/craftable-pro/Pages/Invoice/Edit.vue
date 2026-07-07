<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Invoice')"
        :subtitle="`Last updated at ${dayjs(
            invoice.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <div class="flex items-center gap-3">
            <Button
                :leftIcon="ArrowDownTrayIcon"
                @click="submit"
                :loading="form.processing"
                variant="outline"
                color="gray"
                v-can="'craftable-pro.invoices.edit'"
            >
                {{ $t("craftable-pro", "Save") }}
            </Button>
            <Button
                :leftIcon="BanknotesIcon"
                @click="markPaid"
                :loading="paying"
                :disabled="invoice.is_paid"
                v-can="'craftable-pro.invoices.pay'"
            >
                {{ invoice.is_paid ? $t("craftable-pro", "Paid") : $t("craftable-pro", "Mark Paid") }}
            </Button>
        </div>
    </PageHeader>

    <Form :form="form" :submit="submit"  />
</template>

<script setup lang="ts">
import { ref } from "vue";
import { ArrowDownTrayIcon, BanknotesIcon } from "@heroicons/vue/24/outline";
import { router } from "@inertiajs/vue3";
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

const paying = ref(false);

const markPaid = () => {
    router.post(
        route("craftable-pro.invoices.pay", props.invoice.id),
        {},
        {
            preserveScroll: true,
            onStart: () => (paying.value = true),
            onFinish: () => (paying.value = false),
        }
    );
};

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
