<template>
    <PageContent>
        <div class="mx-auto max-w-3xl 2xl:max-w-4xl">
            

            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1 space-y-4">
                    <Card>
                        <div class="space-y-4">
                            <Multiselect
                                v-model="form.store_id"
                                name="store_id"
                                :label="$t('craftable-pro', 'Store')"
                                mode="single"
                                :options="$page.props.stores ?? []"
                                options-value-prop="id"
                                options-label="name"
                                :searchable="true"
                            />
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
                                v-model="form.document_id"
                                name="document_id"
                                :label="$t('craftable-pro', 'Document')"
                                mode="single"
                                :options="$page.props.documents ?? []"
                                options-value-prop="id"
                                options-label="number"
                                :searchable="true"
                            />
                            <TextInput
                                v-model="form.initial_stock_quantity"
                                name="initial_stock_quantity"
                                :label="$t('craftable-pro', 'Initial Stock Quantity')"
                                type="number"
                            />
                            <TextInput
                                v-model="form.initial_item_cost"
                                name="initial_item_cost"
                                :label="$t('craftable-pro', 'Initial Item Cost')"
                                type="number"
                            />
                            <Checkbox
                                v-model="form.is_stock_entry"
                                name="is_stock_entry"
                                :label="$t('craftable-pro', 'Is Stock Entry')"
                            />
                            <TextInput
                                v-model="form.quantity"
                                name="quantity"
                                :label="$t('craftable-pro', 'Quantity')"
                                type="number"
                            />
                            <TextInput
                                v-model="form.current_stock_quantity"
                                name="current_stock_quantity"
                                :label="$t('craftable-pro', 'Current Stock Quantity')"
                                type="number"
                            />
                            <TextInput
                                v-model="form.current_item_cost"
                                name="current_item_cost"
                                :label="$t('craftable-pro', 'Current Item Cost')"
                                type="number"
                            />
                            <TextInput
                                v-model="form.description"
                                name="description"
                                :label="$t('craftable-pro', 'Description')"
                                type="text"
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
import type { StockHistoryForm } from "./types";



interface Props {
    form: InertiaForm<StockHistoryForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
