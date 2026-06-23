<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Loyalty Card Type')"
        :subtitle="`Last updated at ${dayjs(
            loyaltyCardType.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.loyalty-card-types.edit'"
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
import type { LoyaltyCardType, LoyaltyCardTypeForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    loyaltyCardType: LoyaltyCardType;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<LoyaltyCardTypeForm>(
    {
        name: props.loyaltyCardType?.name ?? "",
        description: props.loyaltyCardType?.description ?? "",
        is_active: props.loyaltyCardType?.is_active ?? false
    },
    route("craftable-pro.loyalty-card-types.update", [props.loyaltyCardType?.id])
);
</script>
