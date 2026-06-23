<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Discount Type')"
        :subtitle="`Last updated at ${dayjs(
            discountType.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.discount-types.edit'"
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
import type { DiscountType, DiscountTypeForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    discountType: DiscountType;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<DiscountTypeForm>(
    {
        store_id: props.discountType?.store_id ?? "",
        loyalty_card_type_id: props.discountType?.loyalty_card_type_id ?? "",
        name: props.discountType?.name ?? "",
        description: props.discountType?.description ?? "",
        is_percentage: props.discountType?.is_percentage ?? false,
        value: props.discountType?.value ?? "",
        coupon_code: props.discountType?.coupon_code ?? "",
        min_order_value: props.discountType?.min_order_value ?? "",
        min_item_quantity: props.discountType?.min_item_quantity ?? "",
        apply_to_all: props.discountType?.apply_to_all ?? false,
        apply_to_next: props.discountType?.apply_to_next ?? false,
        max_discount_value: props.discountType?.max_discount_value ?? "",
        start_time: props.discountType?.start_time ?? "",
        end_time: props.discountType?.end_time ?? "",
        is_active: props.discountType?.is_active ?? false,
        comments: props.discountType?.comments ?? ""
    },
    route("craftable-pro.discount-types.update", [props.discountType?.id])
);
</script>
