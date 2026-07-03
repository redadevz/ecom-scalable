<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Item')"
        :subtitle="`Last updated at ${dayjs(
            item.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.items.edit'"
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
import type { Item, ItemForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    item: Item;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<ItemForm>(
    {
        store_id: props.item?.store_id ?? "",
        item_category_id: props.item?.item_category_id ?? "",
        supplier_id: props.item?.supplier_id ?? "",
        measure_unit_id: props.item?.measure_unit_id ?? "",
        sku_code: props.item?.sku_code ?? "",
        name: props.item?.name ?? "",
        images: props.item?.images ?? [],
        description: props.item?.description ?? "",
        is_service: props.item?.is_service ?? false,
        in_stock: props.item?.in_stock ?? false,
        using_default_quantity: props.item?.using_default_quantity ?? false,
        default_quantity: props.item?.default_quantity ?? "",
        current_stock_quantity: props.item?.current_stock_quantity ?? "",
        preferred_stock_quantity: props.item?.preferred_stock_quantity ?? "",
        min_stock_quantity: props.item?.min_stock_quantity ?? "",
        low_stock_warning: props.item?.low_stock_warning ?? false,
        low_stock_quantity: props.item?.low_stock_quantity ?? "",
        is_active: props.item?.is_active ?? false,
        comments: props.item?.comments ?? ""
    },
    route("craftable-pro.items.update", [props.item?.id])
);
</script>
