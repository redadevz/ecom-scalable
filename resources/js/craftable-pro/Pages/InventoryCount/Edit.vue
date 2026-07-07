<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Inventory Count')"
        :subtitle="`Last updated at ${dayjs(
            inventoryCount.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <div class="flex items-center gap-3">
            <Button
                :leftIcon="ArrowDownTrayIcon"
                @click="submit"
                :loading="form.processing"
                variant="outline"
                color="gray"
                v-can="'craftable-pro.inventory-counts.edit'"
            >
                {{ $t("craftable-pro", "Save") }}
            </Button>
            <Button
                :leftIcon="ClipboardDocumentCheckIcon"
                @click="applyCount"
                :loading="applying"
                :disabled="!!inventoryCount.change_stock_time"
                v-can="'craftable-pro.inventory-counts.apply'"
            >
                {{ inventoryCount.change_stock_time ? $t("craftable-pro", "Applied") : $t("craftable-pro", "Apply Count") }}
            </Button>
        </div>
    </PageHeader>

    <Form :form="form" :submit="submit"  />
</template>

<script setup lang="ts">
import { ref } from "vue";
import { ArrowDownTrayIcon, ClipboardDocumentCheckIcon } from "@heroicons/vue/24/outline";
import { router } from "@inertiajs/vue3";
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

const applying = ref(false);

const applyCount = () => {
    router.post(
        route("craftable-pro.inventory-counts.apply", props.inventoryCount.id),
        {},
        {
            preserveScroll: true,
            onStart: () => (applying.value = true),
            onFinish: () => (applying.value = false),
        }
    );
};

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
