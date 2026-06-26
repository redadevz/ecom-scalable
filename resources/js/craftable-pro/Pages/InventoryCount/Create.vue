<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Create Inventory Count')">
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.inventory-counts.create'"
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
import type { InventoryCountForm } from "./types";
import dayjs from 'dayjs'



interface Props {
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<InventoryCountForm>(
    {
        store_id: "",
        physical_count_time: "",
        change_stock_time: "",
        description: "",
        comments: ""
    },
    route("craftable-pro.inventory-counts.store"),
    "post"
);
</script>
