<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Cart Item')"
        :subtitle="`Last updated at ${dayjs(
            cartItem.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.cart-items.edit'"
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
import type { CartItem, CartItemForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    cartItem: CartItem;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<CartItemForm>(
    {
        cart_id: props.cartItem?.cart_id ?? "",
        product_id: props.cartItem?.product_id ?? "",
        quantity: props.cartItem?.quantity ?? "",
        unit_price: props.cartItem?.unit_price ?? ""
    },
    route("craftable-pro.cart-items.update", [props.cartItem?.id])
);
</script>
