<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Item Tax Type')"
        :subtitle="`Last updated at ${dayjs(
            itemTaxType.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.item-tax-types.edit'"
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
import type { ItemTaxType, ItemTaxTypeForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    itemTaxType: ItemTaxType;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<ItemTaxTypeForm>(
    {
        item_id: props.itemTaxType?.item_id ?? "",
        tax_type_id: props.itemTaxType?.tax_type_id ?? "",
        start_time: props.itemTaxType?.start_time ?? "",
        end_time: props.itemTaxType?.end_time ?? "",
        description: props.itemTaxType?.description ?? ""
    },
    route("craftable-pro.item-tax-types.update", [props.itemTaxType?.id])
);
</script>
