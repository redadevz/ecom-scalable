<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Holiday')"
        :subtitle="`Last updated at ${dayjs(
            holiday.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.holidays.edit'"
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
import type { Holiday, HolidayForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    holiday: Holiday;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<HolidayForm>(
    {
        store_id: props.holiday?.store_id ?? "",
        name: props.holiday?.name ?? "",
        reason: props.holiday?.reason ?? "",
        starts_at: props.holiday?.starts_at ?? "",
        ends_at: props.holiday?.ends_at ?? "",
        comments: props.holiday?.comments ?? ""
    },
    route("craftable-pro.holidays.update", [props.holiday?.id])
);
</script>
