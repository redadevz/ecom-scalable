<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Language')"
        :subtitle="`Last updated at ${dayjs(
            language.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.languages.edit'"
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
import type { Language, LanguageForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";


dayjs.extend(customParseFormat);



interface Props {
    language: Language;
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<LanguageForm>(
    {
        name: props.language?.name ?? "",
        short_name: props.language?.short_name ?? "",
        description: props.language?.description ?? ""
    },
    route("craftable-pro.languages.update", [props.language?.id])
);
</script>
