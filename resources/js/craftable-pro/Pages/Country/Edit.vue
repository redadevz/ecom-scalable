<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Country')"
        :subtitle="`Last updated at ${dayjs(
            country.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.countries.edit'"
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
import type { Country, CountryForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    country: Country;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<CountryForm>(
    {
        name: props.country?.name ?? ""
    },
    route("craftable-pro.countries.update", [props.country?.id])
);
</script>
