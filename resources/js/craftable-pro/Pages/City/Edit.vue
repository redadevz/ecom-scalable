<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit City')"
        :subtitle="`Last updated at ${dayjs(
            city.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.cities.edit'"
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
import type { City, CityForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    city: City;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<CityForm>(
    {
        name: props.city?.name ?? "",
        region_id: props.city?.region_id ?? "",
        timezone_id: props.city?.timezone_id ?? "",
        zipcode: props.city?.zipcode ?? ""
    },
    route("craftable-pro.cities.update", [props.city?.id])
);
</script>
