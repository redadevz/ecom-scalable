<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Create Invoice Line')">
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.invoice-lines.create'"
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
import type { InvoiceLineForm } from "./types";
import dayjs from 'dayjs'



interface Props {
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<InvoiceLineForm>(
    {
        invoice_id: "",
        order_line_id: "",
        line_no: "",
        comments: ""
    },
    route("craftable-pro.invoice-lines.store"),
    "post"
);
</script>
