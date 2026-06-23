<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Loyalty Card')"
        :subtitle="`Last updated at ${dayjs(
            loyaltyCard.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.loyalty-cards.edit'"
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
import type { LoyaltyCard, LoyaltyCardForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    loyaltyCard: LoyaltyCard;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<LoyaltyCardForm>(
    {
        loyalty_card_type_id: props.loyaltyCard?.loyalty_card_type_id ?? "",
        customer_id: props.loyaltyCard?.customer_id ?? "",
        code: props.loyaltyCard?.code ?? "",
        is_active: props.loyaltyCard?.is_active ?? false
    },
    route("craftable-pro.loyalty-cards.update", [props.loyaltyCard?.id])
);
</script>
