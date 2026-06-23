<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Price')"
        :subtitle="`Last updated at ${dayjs(
            price.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.prices.edit'"
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
import type { Price, PriceForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    price: Price;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<PriceForm>(
    {
        item_id: props.price?.item_id ?? "",
        store_id: props.price?.store_id ?? "",
        created_by: props.price?.created_by ?? "",
        description: props.price?.description ?? "",
        current_item_cost: props.price?.current_item_cost ?? "",
        markup_percentage: props.price?.markup_percentage ?? "",
        price_before_tax: props.price?.price_before_tax ?? "",
        tax_value: props.price?.tax_value ?? "",
        price_after_tax: props.price?.price_after_tax ?? "",
        sale_price: props.price?.sale_price ?? "",
        price_change_allowed: props.price?.price_change_allowed ?? false,
        start_time: props.price?.start_time ?? "",
        end_time: props.price?.end_time ?? "",
        is_active: props.price?.is_active ?? false,
        comments: props.price?.comments ?? ""
    },
    route("craftable-pro.prices.update", [props.price?.id])
);
</script>
