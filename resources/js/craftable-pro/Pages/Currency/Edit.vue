<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Currency')"
        :subtitle="`Last updated at ${dayjs(
            currency.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.currencies.edit'"
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
import type { Currency, CurrencyForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    currency: Currency;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<CurrencyForm>(
    {
        name: props.currency?.name ?? "",
        short_name: props.currency?.short_name ?? "",
        symbol: props.currency?.symbol ?? "",
        description: props.currency?.description ?? ""
    },
    route("craftable-pro.currencies.update", [props.currency?.id])
);
</script>
