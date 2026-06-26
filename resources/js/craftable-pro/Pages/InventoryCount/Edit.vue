<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Inventory Count')"
        :subtitle="`Last updated at ${dayjs(
            inventoryCount.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.inventory-counts.edit'"
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
import type { InventoryCount, InventoryCountForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    inventoryCount: InventoryCount;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<InventoryCountForm>(
    {
        store_id: props.inventoryCount?.store_id ?? "",
        physical_count_time: props.inventoryCount?.physical_count_time ?? "",
        change_stock_time: props.inventoryCount?.change_stock_time ?? "",
        description: props.inventoryCount?.description ?? "",
        comments: props.inventoryCount?.comments ?? ""
    },
    route("craftable-pro.inventory-counts.update", [props.inventoryCount?.id])
);
</script>
