<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Create Item')">
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.items.create'"
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
import type { ItemForm } from "./types";
import dayjs from 'dayjs'



interface Props {
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<ItemForm>(
    {
        store_id: "",
        item_category_id: "",
        supplier_id: "",
        measure_unit_id: "",
        sku_code: "",
        name: "",
        images: [],
        description: "",
        is_service: false,
        in_stock: false,
        using_default_quantity: false,
        default_quantity: "",
        current_stock_quantity: "",
        preferred_stock_quantity: "",
        min_stock_quantity: "",
        low_stock_warning: false,
        low_stock_quantity: "",
        is_active: false,
        comments: ""
    },
    route("craftable-pro.items.store"),
    "post"
);
</script>
