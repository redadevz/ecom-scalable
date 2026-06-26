<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Purchase Item')"
        :subtitle="`Last updated at ${dayjs(
            purchaseItem.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.purchase-items.edit'"
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
import type { PurchaseItem, PurchaseItemForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    purchaseItem: PurchaseItem;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<PurchaseItemForm>(
    {
        purchase_id: props.purchaseItem?.purchase_id ?? "",
        item_id: props.purchaseItem?.item_id ?? "",
        supplier_price_before_tax: props.purchaseItem?.supplier_price_before_tax ?? "",
        supplier_tax_value: props.purchaseItem?.supplier_tax_value ?? "",
        supplier_price_after_tax: props.purchaseItem?.supplier_price_after_tax ?? "",
        supplier_discount_value: props.purchaseItem?.supplier_discount_value ?? "",
        quantity: props.purchaseItem?.quantity ?? "",
        return_amount: props.purchaseItem?.return_amount ?? "",
        description: props.purchaseItem?.description ?? "",
        comments: props.purchaseItem?.comments ?? ""
    },
    route("craftable-pro.purchase-items.update", [props.purchaseItem?.id])
);
</script>
