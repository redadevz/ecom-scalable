<template>
    <PageContent>
        <div class="mx-auto max-w-3xl 2xl:max-w-4xl">
            

            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1 space-y-4">
                    <Card>
                        <div class="space-y-4">
                            <Multiselect
                                v-model="form.item_id"
                                name="item_id"
                                :label="$t('craftable-pro', 'Item')"
                                mode="single"
                                :options="$page.props.items ?? []"
                                options-value-prop="id"
                                options-label="name"
                                :searchable="true"
                            />
                            <Multiselect
                                v-model="form.supplier_tax_type_id"
                                name="supplier_tax_type_id"
                                :label="$t('craftable-pro', 'Supplier Tax Type')"
                                mode="single"
                                :options="$page.props.supplier_tax_types ?? []"
                                options-value-prop="id"
                                options-label="name"
                                :searchable="true"
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
                            <TextInput
                                v-model="form.description"
                                name="description"
                                :label="$t('craftable-pro', 'Description')"
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
import type { SupplierItemTaxTypeForm } from "./types";



interface Props {
    form: InertiaForm<SupplierItemTaxTypeForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
