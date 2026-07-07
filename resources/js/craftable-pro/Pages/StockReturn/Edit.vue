<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Stock Return')"
        :subtitle="`Last updated at ${dayjs(
            stockReturn.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <div class="flex items-center gap-3">
            <Button
                :leftIcon="ArrowDownTrayIcon"
                @click="submit"
                :loading="form.processing"
                variant="outline"
                color="gray"
                v-can="'craftable-pro.stock-returns.edit'"
            >
                {{ $t("craftable-pro", "Save") }}
            </Button>
            <Button
                :leftIcon="ArrowUturnRightIcon"
                @click="processReturn"
                :loading="processing"
                :disabled="!!stockReturn.exit_stock_time"
                v-can="'craftable-pro.stock-returns.process'"
            >
                {{ stockReturn.exit_stock_time ? $t("craftable-pro", "Processed") : $t("craftable-pro", "Process Return") }}
            </Button>
        </div>
    </PageHeader>

    <Form :form="form" :submit="submit"  />
</template>

<script setup lang="ts">
import { ref } from "vue";
import { ArrowDownTrayIcon, ArrowUturnRightIcon } from "@heroicons/vue/24/outline";
import { router } from "@inertiajs/vue3";
import { PageHeader, Button } from "craftable-pro/Components";
import { useForm } from "craftable-pro/hooks/useForm";
import Form from "./Form.vue";
import type { StockReturn, StockReturnForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    stockReturn: StockReturn;

}

const props = defineProps<Props>();

const processing = ref(false);

const processReturn = () => {
    router.post(
        route("craftable-pro.stock-returns.process", props.stockReturn.id),
        {},
        {
            preserveScroll: true,
            onStart: () => (processing.value = true),
            onFinish: () => (processing.value = false),
        }
    );
};

const { form, submit } = useForm<StockReturnForm>(
    {
        store_id: props.stockReturn?.store_id ?? "",
        purchase_id: props.stockReturn?.purchase_id ?? "",
        exit_stock_time: props.stockReturn?.exit_stock_time ?? "",
        description: props.stockReturn?.description ?? "",
        is_paid: props.stockReturn?.is_paid ?? false,
        comments: props.stockReturn?.comments ?? ""
    },
    route("craftable-pro.stock-returns.update", [props.stockReturn?.id])
);
</script>
