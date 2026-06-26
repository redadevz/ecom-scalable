<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Inventory Count Item')"
        :subtitle="`Last updated at ${dayjs(
            inventoryCountItem.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.inventory-count-items.edit'"
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
import type { InventoryCountItem, InventoryCountItemForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    inventoryCountItem: InventoryCountItem;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<InventoryCountItemForm>(
    {
        inventory_count_id: props.inventoryCountItem?.inventory_count_id ?? "",
        item_id: props.inventoryCountItem?.item_id ?? "",
        quantity_counted: props.inventoryCountItem?.quantity_counted ?? "",
        quantity_expected: props.inventoryCountItem?.quantity_expected ?? "",
        quantity_change: props.inventoryCountItem?.quantity_change ?? "",
        description: props.inventoryCountItem?.description ?? "",
        comments: props.inventoryCountItem?.comments ?? ""
    },
    route("craftable-pro.inventory-count-items.update", [props.inventoryCountItem?.id])
);
</script>
