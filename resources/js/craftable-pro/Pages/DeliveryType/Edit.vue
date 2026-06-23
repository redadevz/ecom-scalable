<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Delivery Type')"
        :subtitle="`Last updated at ${dayjs(
            deliveryType.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.delivery-types.edit'"
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
import type { DeliveryType, DeliveryTypeForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    deliveryType: DeliveryType;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<DeliveryTypeForm>(
    {
        name: props.deliveryType?.name ?? "",
        description: props.deliveryType?.description ?? "",
        is_active: props.deliveryType?.is_active ?? false
    },
    route("craftable-pro.delivery-types.update", [props.deliveryType?.id])
);
</script>
