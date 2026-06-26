<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Create Inventory Count Item')">
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.inventory-count-items.create'"
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
import type { InventoryCountItemForm } from "./types";
import dayjs from 'dayjs'



interface Props {
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<InventoryCountItemForm>(
    {
        inventory_count_id: "",
        item_id: "",
        quantity_counted: "",
        quantity_expected: "",
        quantity_change: "",
        description: "",
        comments: ""
    },
    route("craftable-pro.inventory-count-items.store"),
    "post"
);
</script>
