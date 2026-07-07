<template>
    <PageHeader
        sticky
        :title="$t('craftable-pro', 'Edit Loss And Damage')"
        :subtitle="`Last updated at ${dayjs(
            lossAndDamage.updated_at
        ).format('DD.MM.YYYY')}`"
    >
        <div class="flex items-center gap-3">
            <Button
                :leftIcon="ArrowDownTrayIcon"
                @click="submit"
                :loading="form.processing"
                variant="outline"
                color="gray"
                v-can="'craftable-pro.loss-and-damages.edit'"
            >
                {{ $t("craftable-pro", "Save") }}
            </Button>
            <Button
                :leftIcon="ArchiveBoxXMarkIcon"
                @click="writeOff"
                :loading="writingOff"
                :disabled="!!lossAndDamage.exit_stock_time"
                color="danger"
                v-can="'craftable-pro.loss-and-damages.apply'"
            >
                {{ lossAndDamage.exit_stock_time ? $t("craftable-pro", "Written Off") : $t("craftable-pro", "Write Off") }}
            </Button>
        </div>
    </PageHeader>

    <Form :form="form" :submit="submit"  />
</template>

<script setup lang="ts">
import { ref } from "vue";
import { ArrowDownTrayIcon, ArchiveBoxXMarkIcon } from "@heroicons/vue/24/outline";
import { router } from "@inertiajs/vue3";
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

const writingOff = ref(false);

const writeOff = () => {
    if (!confirm("Write off these items? Stock will be permanently reduced.")) {
        return;
    }
    router.post(
        route("craftable-pro.loss-and-damages.apply", props.lossAndDamage.id),
        {},
        {
            preserveScroll: true,
            onStart: () => (writingOff.value = true),
            onFinish: () => (writingOff.value = false),
        }
    );
};

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
