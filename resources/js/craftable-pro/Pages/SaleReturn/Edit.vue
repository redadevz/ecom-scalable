<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Sale Return')"
        :subtitle="`Last updated at ${dayjs(
            saleReturn.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <div class="flex items-center gap-3">
            <Button
                :leftIcon="ArrowDownTrayIcon"
                @click="submit"
                :loading="form.processing"
                variant="outline"
                color="gray"
                v-can="'craftable-pro.sale-returns.edit'"
            >
                {{ $t("craftable-pro", "Save") }}
            </Button>
            <Button
                :leftIcon="ArrowUturnLeftIcon"
                @click="processReturn"
                :loading="processing"
                :disabled="!!saleReturn.entry_stock_time"
                v-can="'craftable-pro.sale-returns.edit'"
            >
                {{ saleReturn.entry_stock_time ? $t("craftable-pro", "Processed") : $t("craftable-pro", "Process Return") }}
            </Button>
            <Button
                :leftIcon="BanknotesIcon"
                @click="refund"
                :loading="refunding"
                :disabled="!!saleReturn.is_refunded"
                v-can="'craftable-pro.sale-returns.edit'"
            >
                {{ saleReturn.is_refunded ? $t("craftable-pro", "Refunded") : $t("craftable-pro", "Refund") }}
            </Button>
        </div>
    </PageHeader>

    <Form :form="form" :submit="submit"  />
</template>

<script setup lang="ts">
import { ref } from "vue";
import { ArrowDownTrayIcon, ArrowUturnLeftIcon, BanknotesIcon } from "@heroicons/vue/24/outline";
import { router } from "@inertiajs/vue3";
import { PageHeader, Button } from "craftable-pro/Components";
import { useForm } from "craftable-pro/hooks/useForm";
import Form from "./Form.vue";
import type { SaleReturn, SaleReturnForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    saleReturn: SaleReturn;

}

const props = defineProps<Props>();

const processing = ref(false);

const processReturn = () => {
    router.post(
        route("craftable-pro.sale-returns.process", props.saleReturn.id),
        {},
        {
            preserveScroll: true,
            onStart: () => (processing.value = true),
            onFinish: () => (processing.value = false),
        }
    );
};

const refunding = ref(false);

const refund = () => {
    router.post(
        route("craftable-pro.sale-returns.refund", props.saleReturn.id),
        {},
        {
            preserveScroll: true,
            onStart: () => (refunding.value = true),
            onFinish: () => (refunding.value = false),
        }
    );
};

const { form, submit } = useForm<SaleReturnForm>(
    {
        store_id: props.saleReturn?.store_id ?? "",
        order_id: props.saleReturn?.order_id ?? "",
        entry_stock_time: props.saleReturn?.entry_stock_time ?? "",
        is_refund_required: props.saleReturn?.is_refund_required ?? false,
        refund_amount: props.saleReturn?.refund_amount ?? "",
        is_refunded: props.saleReturn?.is_refunded ?? false,
        refund_time: props.saleReturn?.refund_time ?? "",
        description: props.saleReturn?.description ?? "",
        comments: props.saleReturn?.comments ?? ""
    },
    route("craftable-pro.sale-returns.update", [props.saleReturn?.id])
);
</script>
