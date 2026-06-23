<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Create Tax Type')">
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.tax-types.create'"
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
import type { TaxTypeForm } from "./types";
import dayjs from 'dayjs'



interface Props {
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<TaxTypeForm>(
    {
        store_id: "",
        name: "",
        code: "",
        description: "",
        is_percentage: false,
        value: "",
        start_time: "",
        end_time: "",
        is_active: false,
        comments: ""
    },
    route("craftable-pro.tax-types.store"),
    "post"
);
</script>
