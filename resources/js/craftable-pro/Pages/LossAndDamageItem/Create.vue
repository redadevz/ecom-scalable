<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Create Loss And Damage Item')">
        <Button
            :leftIcon="ArrowDownTrayIcon"
            @click="submit"
            :loading="form.processing"
            v-can="'craftable-pro.loss-and-damage-items.create'"
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
import type { LossAndDamageItemForm } from "./types";
import dayjs from 'dayjs'



interface Props {
    
}

const props = defineProps<Props>();

const { form, submit } = useForm<LossAndDamageItemForm>(
    {
        loss_and_damage_id: "",
        item_id: "",
        quantity: "",
        description: "",
        comments: ""
    },
    route("craftable-pro.loss-and-damage-items.store"),
    "post"
);
</script>
