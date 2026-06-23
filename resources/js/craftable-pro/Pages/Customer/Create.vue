<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Create Customer')">
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.customers.create'"
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
import type { CustomerForm } from "./types";
import dayjs from 'dayjs'



interface Props {
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<CustomerForm>(
    {
        city_id: "",
        created_at_store_id: "",
        created_by: "",
        code: "",
        phone: "",
        first_name: "",
        last_name: "",
        is_company: false,
        company_name: "",
        tax_number: "",
        is_tax_exempted: false,
        billing_address: "",
        postal_code: "",
        is_registered_online: false,
        email: "",
        username: "",
        password: "",
        credit: "",
        last_login_time: "",
        is_active: false,
        comments: ""
    },
    route("craftable-pro.customers.store"),
    "post"
);
</script>
