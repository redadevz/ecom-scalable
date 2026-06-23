<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Discount')"
        :subtitle="`Last updated at ${dayjs(
            discount.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.discounts.edit'"
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
import type { Discount, DiscountForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    discount: Discount;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<DiscountForm>(
    {
        discount_type_id: props.discount?.discount_type_id ?? "",
        item_category_id: props.discount?.item_category_id ?? "",
        item_id: props.discount?.item_id ?? "",
        description: props.discount?.description ?? "",
        comments: props.discount?.comments ?? ""
    },
    route("craftable-pro.discounts.update", [props.discount?.id])
);
</script>
