<template>
    <PageContent>
        <div class="mx-auto max-w-3xl 2xl:max-w-4xl">
            

            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1 space-y-4">
                    <Card>
                        <div class="space-y-4">
                            <TextInput
                                v-model="form.name"
                                name="name"
                                :label="$t('craftable-pro', 'Name')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.code"
                                name="code"
                                :label="$t('craftable-pro', 'Code')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.sequence_no"
                                name="sequence_no"
                                :label="$t('craftable-pro', 'Sequence No')"
                                type="text"
                            />
                            <Checkbox
                                v-model="form.is_active"
                                name="is_active"
                                :label="$t('craftable-pro', 'Is Active')"
                            />
                            <Checkbox
                                v-model="form.is_customer_required"
                                name="is_customer_required"
                                :label="$t('craftable-pro', 'Is Customer Required')"
                            />
                            <TextArea
                                v-model="form.description"
                                name="description"
                                :label="$t('craftable-pro', 'Description')"
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
import type { PaymentMethodForm } from "./types";



interface Props {
    form: InertiaForm<PaymentMethodForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
