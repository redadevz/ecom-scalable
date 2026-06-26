<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Loss And Damage Item')"
        :subtitle="`Last updated at ${dayjs(
            lossAndDamageItem.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.loss-and-damage-items.edit'"
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
import type { LossAndDamageItem, LossAndDamageItemForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    lossAndDamageItem: LossAndDamageItem;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<LossAndDamageItemForm>(
    {
        loss_and_damage_id: props.lossAndDamageItem?.loss_and_damage_id ?? "",
        item_id: props.lossAndDamageItem?.item_id ?? "",
        quantity: props.lossAndDamageItem?.quantity ?? "",
        description: props.lossAndDamageItem?.description ?? "",
        comments: props.lossAndDamageItem?.comments ?? ""
    },
    route("craftable-pro.loss-and-damage-items.update", [props.lossAndDamageItem?.id])
);
</script>
