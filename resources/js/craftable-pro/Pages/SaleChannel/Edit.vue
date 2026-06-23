<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Sale Channel')"
        :subtitle="`Last updated at ${dayjs(
            saleChannel.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.sale-channels.edit'"
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
import type { SaleChannel, SaleChannelForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    saleChannel: SaleChannel;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<SaleChannelForm>(
    {
        name: props.saleChannel?.name ?? "",
        description: props.saleChannel?.description ?? "",
        is_active: props.saleChannel?.is_active ?? false
    },
    route("craftable-pro.sale-channels.update", [props.saleChannel?.id])
);
</script>
