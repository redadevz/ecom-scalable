<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Create Document')">
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.documents.create'"
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
import type { DocumentForm } from "./types";
import dayjs from 'dayjs'



interface Props {
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<DocumentForm>(
    {
        store_id: "",
        document_type_id: "",
        sale_order_id: "",
        purchase_id: "",
        stock_return_id: "",
        inventory_count_id: "",
        loss_and_damage_id: "",
        created_by: "",
        number: "",
        external_number: "",
        description: "",
        comments: ""
    },
    route("craftable-pro.documents.store"),
    "post"
);
</script>
