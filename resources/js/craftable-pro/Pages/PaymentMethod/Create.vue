<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Create Payment Method')">
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.payment-methods.create'"
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
import type { PaymentMethodForm } from "./types";
import dayjs from 'dayjs'



interface Props {
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<PaymentMethodForm>(
    {
        name: "",
        code: "",
        sequence_no: "",
        is_active: false,
        is_customer_required: false,
        description: ""
    },
    route("craftable-pro.payment-methods.store"),
    "post"
);
</script>
