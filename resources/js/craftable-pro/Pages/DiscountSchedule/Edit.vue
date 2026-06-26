<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Discount Schedule')"
        :subtitle="`Last updated at ${dayjs(
            discountSchedule.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.discount-schedules.edit'"
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
import type { DiscountSchedule, DiscountScheduleForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    discountSchedule: DiscountSchedule;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<DiscountScheduleForm>(
    {
        discount_type_id: props.discountSchedule?.discount_type_id ?? "",
        day_of_week: props.discountSchedule?.day_of_week ?? false,
        start_time: props.discountSchedule?.start_time ? dayjs(props.discountSchedule?.start_time, 'HH:mm').format('YYYY/MM/DD HH:mm') : '',
        end_time: props.discountSchedule?.end_time ? dayjs(props.discountSchedule?.end_time, 'HH:mm').format('YYYY/MM/DD HH:mm') : '',
        comments: props.discountSchedule?.comments ?? ""
    },
    route("craftable-pro.discount-schedules.update", [props.discountSchedule?.id])
);
</script>
