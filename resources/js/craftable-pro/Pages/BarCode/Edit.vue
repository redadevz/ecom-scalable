<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Bar Code')"
        :subtitle="`Last updated at ${dayjs(
            barCode.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.bar-codes.edit'"
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
import type { BarCode, BarCodeForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    barCode: BarCode;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<BarCodeForm>(
    {
        item_id: props.barCode?.item_id ?? "",
        bar_code: props.barCode?.bar_code ?? "",
        is_active: props.barCode?.is_active ?? false,
        description: props.barCode?.description ?? ""
    },
    route("craftable-pro.bar-codes.update", [props.barCode?.id])
);
</script>
