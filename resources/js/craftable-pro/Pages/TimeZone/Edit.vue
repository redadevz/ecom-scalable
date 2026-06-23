<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Time Zone')"
        :subtitle="`Last updated at ${dayjs(
            timeZone.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.time-zones.edit'"
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
import type { TimeZone, TimeZoneForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    timeZone: TimeZone;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<TimeZoneForm>(
    {
        name: props.timeZone?.name ?? "",
        description: props.timeZone?.description ?? ""
    },
    route("craftable-pro.time-zones.update", [props.timeZone?.id])
);
</script>
