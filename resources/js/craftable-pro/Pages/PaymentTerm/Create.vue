<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Create Payment Term')">
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.payment-terms.create'"
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
import type { PaymentTermForm } from "./types";
import dayjs from 'dayjs'



interface Props {
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<PaymentTermForm>(
    {
        sale_channel_id: "",
        delivery_type_id: "",
        payment_method_id: "",
        payment_time_id: "",
        is_allowed: false,
        is_active: false,
        comments: ""
    },
    route("craftable-pro.payment-terms.store"),
    "post"
);
</script>
