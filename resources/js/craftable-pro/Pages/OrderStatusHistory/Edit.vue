<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Order Status History')"
        :subtitle="`Last updated at ${dayjs(
            orderStatusHistory.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.order-status-histories.edit'"
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
import type { OrderStatusHistory, OrderStatusHistoryForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    orderStatusHistory: OrderStatusHistory;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<OrderStatusHistoryForm>(
    {
        order_id: props.orderStatusHistory?.order_id ?? "",
        order_status_id: props.orderStatusHistory?.order_status_id ?? "",
        start_time: props.orderStatusHistory?.start_time ?? "",
        end_time: props.orderStatusHistory?.end_time ?? ""
    },
    route("craftable-pro.order-status-histories.update", [props.orderStatusHistory?.id])
);
</script>
