<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Create Document Type')">
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.document-types.create'"
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
import type { DocumentTypeForm } from "./types";
import dayjs from 'dayjs'



interface Props {
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<DocumentTypeForm>(
    {
        document_category_id: "",
        name: "",
        description: "",
        is_active: false,
        comments: ""
    },
    route("craftable-pro.document-types.store"),
    "post"
);
</script>
