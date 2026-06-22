<template>
    <PageContent>
        <div class="mx-auto max-w-3xl 2xl:max-w-4xl">
            

            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1 space-y-4">
                    <Card>
                        <div class="space-y-4">
                            <TextInput
                                v-model="form.order_id"
                                name="order_id"
                                :label="$t('craftable-pro', 'Order Id')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.provider"
                                name="provider"
                                :label="$t('craftable-pro', 'Provider')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.provider_reference"
                                name="provider_reference"
                                :label="$t('craftable-pro', 'Provider Reference')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.status"
                                name="status"
                                :label="$t('craftable-pro', 'Status')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.amount"
                                name="amount"
                                :label="$t('craftable-pro', 'Amount')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.currency"
                                name="currency"
                                :label="$t('craftable-pro', 'Currency')"
                                type="text"
                            />
                        </div>
                    </Card>
                </div>
            </div>
        </div>
    </PageContent>
</template>

<script setup lang="ts">
import { ref } from "vue"
import {
    Card,
    TextInput,
    Wysiwyg,
    TextArea,
    PageContent,
    DatePicker,
    Checkbox,
    Dropzone,
    ImageUpload,
    Multiselect,
    CardLocaleSwitcher
} from "craftable-pro/Components";
import { InertiaForm } from "craftable-pro/types";
import type { PaymentForm } from "./types";



interface Props {
    form: InertiaForm<PaymentForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
