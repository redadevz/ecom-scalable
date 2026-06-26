<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Document Category')"
        :subtitle="`Last updated at ${dayjs(
            documentCategory.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.document-categories.edit'"
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
import type { DocumentCategory, DocumentCategoryForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    documentCategory: DocumentCategory;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<DocumentCategoryForm>(
    {
        name: props.documentCategory?.name ?? "",
        description: props.documentCategory?.description ?? "",
        is_active: props.documentCategory?.is_active ?? false,
        comments: props.documentCategory?.comments ?? ""
    },
    route("craftable-pro.document-categories.update", [props.documentCategory?.id])
);
</script>
