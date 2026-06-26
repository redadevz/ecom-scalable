<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Purchase')"
        :subtitle="`Last updated at ${dayjs(
            purchase.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.purchases.edit'"
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
import type { Purchase, PurchaseForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    purchase: Purchase;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<PurchaseForm>(
    {
        store_id: props.purchase?.store_id ?? "",
        supplier_id: props.purchase?.supplier_id ?? "",
        entry_stock_time: props.purchase?.entry_stock_time ?? "",
        description: props.purchase?.description ?? "",
        is_paid: props.purchase?.is_paid ?? false,
        comments: props.purchase?.comments ?? ""
    },
    route("craftable-pro.purchases.update", [props.purchase?.id])
);
</script>
