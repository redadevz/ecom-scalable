<template>
    <PageContent>
        <div class="mx-auto max-w-3xl 2xl:max-w-4xl">
            

            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1 space-y-4">
                    <Card>
                        <div class="space-y-4">
                            <Multiselect
                                v-model="form.supplier_id"
                                name="supplier_id"
                                :label="$t('craftable-pro', 'Supplier')"
                                mode="single"
                                :options="$page.props.suppliers ?? []"
                                options-value-prop="id"
                                options-label="code"
                                :searchable="true"
                            />
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
                                v-model="form.description"
                                name="description"
                                :label="$t('craftable-pro', 'Description')"
                                type="text"
                            />
                            <Checkbox
                                v-model="form.is_percentage"
                                name="is_percentage"
                                :label="$t('craftable-pro', 'Is Percentage')"
                            />
                            <TextInput
                                v-model="form.value"
                                name="value"
                                :label="$t('craftable-pro', 'Value')"
                                type="number"
                            />
                            <DatePicker
                                v-model="form.start_time"
                                name="start_time"
                                mode="dateTime"
                                :label="$t('craftable-pro', 'Start Time')"
                                
                            />
                            <DatePicker
                                v-model="form.end_time"
                                name="end_time"
                                mode="dateTime"
                                :label="$t('craftable-pro', 'End Time')"
                                
                            />
                            <Checkbox
                                v-model="form.is_active"
                                name="is_active"
                                :label="$t('craftable-pro', 'Is Active')"
                            />
                            <TextInput
                                v-model="form.comments"
                                name="comments"
                                :label="$t('craftable-pro', 'Comments')"
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
import type { SupplierTaxTypeForm } from "./types";



interface Props {
    form: InertiaForm<SupplierTaxTypeForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
