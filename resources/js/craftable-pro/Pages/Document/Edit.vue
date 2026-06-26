<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Document')"
        :subtitle="`Last updated at ${dayjs(
            document.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.documents.edit'"
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
import type { Document, DocumentForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    document: Document;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<DocumentForm>(
    {
        store_id: props.document?.store_id ?? "",
        document_type_id: props.document?.document_type_id ?? "",
        sale_order_id: props.document?.sale_order_id ?? "",
        purchase_id: props.document?.purchase_id ?? "",
        stock_return_id: props.document?.stock_return_id ?? "",
        inventory_count_id: props.document?.inventory_count_id ?? "",
        loss_and_damage_id: props.document?.loss_and_damage_id ?? "",
        created_by: props.document?.created_by ?? "",
        number: props.document?.number ?? "",
        external_number: props.document?.external_number ?? "",
        description: props.document?.description ?? "",
        comments: props.document?.comments ?? ""
    },
    route("craftable-pro.documents.update", [props.document?.id])
);
</script>
