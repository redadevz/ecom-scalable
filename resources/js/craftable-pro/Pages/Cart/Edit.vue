<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Cart')"
        :subtitle="`Last updated at ${dayjs(
            cart.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.carts.edit'"
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
import type { Cart, CartForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    cart: Cart;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<CartForm>(
    {
        user_id: props.cart?.user_id ?? ""
    },
    route("craftable-pro.carts.update", [props.cart?.id])
);
</script>
