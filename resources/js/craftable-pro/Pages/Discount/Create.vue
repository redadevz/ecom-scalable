<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Create Discount')">
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.discounts.create'"
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
import type { DiscountForm } from "./types";
import dayjs from 'dayjs'



interface Props {
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<DiscountForm>(
    {
        discount_type_id: "",
        item_category_id: "",
        item_id: "",
        description: "",
        comments: ""
    },
    route("craftable-pro.discounts.store"),
    "post"
);
</script>
