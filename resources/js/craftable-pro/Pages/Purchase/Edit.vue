<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Purchase')"
        :subtitle="`Last updated at ${dayjs(
            purchase.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <div class="flex items-center gap-3">
            <Button
                :leftIcon="ArrowDownTrayIcon"
                @click="submit"
                :loading="form.processing"
                variant="outline"
                color="gray"
                v-can="'craftable-pro.purchases.edit'"
            >
                {{ $t("craftable-pro", "Save") }}
            </Button>
            <Button
                :leftIcon="ArrowDownOnSquareStackIcon"
                @click="receiveStock"
                :loading="receiving"
                :disabled="!!purchase.entry_stock_time"
                v-can="'craftable-pro.purchases.edit'"
            >
                {{ purchase.entry_stock_time ? $t("craftable-pro", "Received") : $t("craftable-pro", "Receive Stock") }}
            </Button>
        </div>
    </PageHeader>

    <Form :form="form" :submit="submit"  />
</template>

<script setup lang="ts">
import { ref } from "vue";
import { ArrowDownTrayIcon, ArrowDownOnSquareStackIcon } from "@heroicons/vue/24/outline";
import { router } from "@inertiajs/vue3";
import { PageHeader, Button } from "craftable-pro/Components";
import { useForm } from "craftable-pro/hooks/useForm";
import Form from "./Form.vue";
import type { Purchase, PurchaseForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    purchase: Purchase;

}

const props = defineProps<Props>();

const receiving = ref(false);

const receiveStock = () => {
    router.post(
        route("craftable-pro.purchases.receive", props.purchase.id),
        {},
        {
            preserveScroll: true,
            onStart: () => (receiving.value = true),
            onFinish: () => (receiving.value = false),
        }
    );
};

const { form, submit } = useForm<PurchaseForm>(
    {
        store_id: props.purchase?.store_id ?? "",
        supplier_id: props.purchase?.supplier_id ?? "",
        entry_stock_time: props.purchase?.entry_stock_time ?? "",
        description: props.purchase?.description ?? "",
        is_paid: props.purchase?.is_paid ?? false,
        comments: props.purchase?.comments ?? ""
    },
    route("craftable-pro.purchases.update", [props.purchase?.id])
);
</script>
