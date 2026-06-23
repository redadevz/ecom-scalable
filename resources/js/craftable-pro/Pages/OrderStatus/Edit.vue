<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Order Status')"
        :subtitle="`Last updated at ${dayjs(
            orderStatus.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.order-statuses.edit'"
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
import type { OrderStatus, OrderStatusForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    orderStatus: OrderStatus;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<OrderStatusForm>(
    {
        name: props.orderStatus?.name ?? "",
        description: props.orderStatus?.description ?? "",
        is_default: props.orderStatus?.is_default ?? false
    },
    route("craftable-pro.order-statuses.update", [props.orderStatus?.id])
);
</script>
