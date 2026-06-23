<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Store')"
        :subtitle="`Last updated at ${dayjs(
            store.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.stores.edit'"
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
import type { Store, StoreForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    store: Store;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<StoreForm>(
    {
        city_id: props.store?.city_id ?? "",
        language_id: props.store?.language_id ?? "",
        currency_id: props.store?.currency_id ?? "",
        code: props.store?.code ?? "",
        name: props.store?.name ?? "",
        is_active: props.store?.is_active ?? false,
        legal_entity_name: props.store?.legal_entity_name ?? "",
        tax_code: props.store?.tax_code ?? "",
        address: props.store?.address ?? "",
        registration_number: props.store?.registration_number ?? "",
        phone: props.store?.phone ?? "",
        fax: props.store?.fax ?? "",
        email: props.store?.email ?? "",
        logo: props.store?.logo ?? "",
        bank_branch: props.store?.bank_branch ?? "",
        bank_code: props.store?.bank_code ?? "",
        bank_account: props.store?.bank_account ?? ""
    },
    route("craftable-pro.stores.update", [props.store?.id])
);
</script>
