<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Measure Unit')"
        :subtitle="`Last updated at ${dayjs(
            measureUnit.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.measure-units.edit'"
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
import type { MeasureUnit, MeasureUnitForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    measureUnit: MeasureUnit;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<MeasureUnitForm>(
    {
        name: props.measureUnit?.name ?? "",
        symbol: props.measureUnit?.symbol ?? "",
        description: props.measureUnit?.description ?? ""
    },
    route("craftable-pro.measure-units.update", [props.measureUnit?.id])
);
</script>
