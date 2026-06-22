<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Create Product')">
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.products.create'"
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
import type { ProductForm } from "./types";
import dayjs from 'dayjs'



interface Props {
    craftableProUserOptions: Array<{value: string|number, label: string}>
}

const props = defineProps<Props>();

const { form, submit } = useForm<ProductForm>(
    {
        name: "",
        slug: "",
        description: "",
        price: "",
        stock: "",
        category: "",
        image_url: ""
    },
    route("craftable-pro.products.store"),
    "post"
);
</script>
