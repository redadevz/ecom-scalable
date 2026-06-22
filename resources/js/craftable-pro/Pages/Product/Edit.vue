<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Product')"
        :subtitle="`Last updated at ${dayjs(
            product.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.products.edit'"
        >
            {{ $t("craftable-pro", "Save") }}
        </Button>
    </PageHeader>

    <Form :form="form" :submit="submit" :craftableProUserOptions="craftableProUserOptions" />
</template>

<script setup lang="ts">
import { ArrowDownTrayIcon } from "@heroicons/vue/24/outline";
import { PageHeader, Button } from "craftable-pro/Components";
import { useForm } from "craftable-pro/hooks/useForm";
import Form from "./Form.vue";
import type { Product, ProductForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    product: Product;
    craftableProUserOptions: Array<{value: string|number, label: string}>
}

const props = defineProps<Props>();

const { form, submit } = useForm<ProductForm>(
    {
        name: props.product?.name ?? "",
        slug: props.product?.slug ?? "",
        description: props.product?.description ?? "",
        price: props.product?.price ?? "",
        stock: props.product?.stock ?? "",
        category: props.product?.category ?? "",
        image_url: props.product?.image_url ?? ""
    },
    route("craftable-pro.products.update", [props.product?.id])
);
</script>
