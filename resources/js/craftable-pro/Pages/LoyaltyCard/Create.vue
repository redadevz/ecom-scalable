<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Create Loyalty Card')">
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.loyalty-cards.create'"
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
import type { LoyaltyCardForm } from "./types";
import dayjs from 'dayjs'



interface Props {
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<LoyaltyCardForm>(
    {
        loyalty_card_type_id: "",
        customer_id: "",
        code: "",
        is_active: false
    },
    route("craftable-pro.loyalty-cards.store"),
    "post"
);
</script>
