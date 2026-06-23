<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Tax Type')"
        :subtitle="`Last updated at ${dayjs(
            taxType.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.tax-types.edit'"
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
import type { TaxType, TaxTypeForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    taxType: TaxType;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<TaxTypeForm>(
    {
        store_id: props.taxType?.store_id ?? "",
        name: props.taxType?.name ?? "",
        code: props.taxType?.code ?? "",
        description: props.taxType?.description ?? "",
        is_percentage: props.taxType?.is_percentage ?? false,
        value: props.taxType?.value ?? "",
        start_time: props.taxType?.start_time ?? "",
        end_time: props.taxType?.end_time ?? "",
        is_active: props.taxType?.is_active ?? false,
        comments: props.taxType?.comments ?? ""
    },
    route("craftable-pro.tax-types.update", [props.taxType?.id])
);
</script>
