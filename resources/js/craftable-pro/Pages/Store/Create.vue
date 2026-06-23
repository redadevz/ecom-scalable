<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Create Store')">
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.stores.create'"
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
import type { StoreForm } from "./types";
import dayjs from 'dayjs'



interface Props {
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<StoreForm>(
    {
        city_id: "",
        language_id: "",
        currency_id: "",
        code: "",
        name: "",
        is_active: false,
        legal_entity_name: "",
        tax_code: "",
        address: "",
        registration_number: "",
        phone: "",
        fax: "",
        email: "",
        logo: "",
        bank_branch: "",
        bank_code: "",
        bank_account: ""
    },
    route("craftable-pro.stores.store"),
    "post"
);
</script>
