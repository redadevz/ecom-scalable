<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Loss And Damage')"
        :subtitle="`Last updated at ${dayjs(
            lossAndDamage.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.loss-and-damages.edit'"
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
import type { LossAndDamage, LossAndDamageForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    lossAndDamage: LossAndDamage;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<LossAndDamageForm>(
    {
        store_id: props.lossAndDamage?.store_id ?? "",
        exit_stock_time: props.lossAndDamage?.exit_stock_time ?? "",
        description: props.lossAndDamage?.description ?? "",
        comments: props.lossAndDamage?.comments ?? ""
    },
    route("craftable-pro.loss-and-damages.update", [props.lossAndDamage?.id])
);
</script>
