<template>
    <PageContent>
        <div class="mx-auto max-w-5xl space-y-6">

            <Card title="Relations">
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                    <Multiselect
                        v-model="form.store_id"
                        name="store_id"
                        label="Store"
                        mode="single"
                        :options="$page.props.stores ?? []"
                        options-value-prop="id"
                        options-label="name"
                        :searchable="true"
                    />
                    <Multiselect
                        v-model="form.item_id"
                        name="item_id"
                        label="Item"
                        mode="single"
                        :options="$page.props.items ?? []"
                        options-value-prop="id"
                        options-label="name"
                        :searchable="true"
                    />
                    <Multiselect
                        v-model="form.document_id"
                        name="document_id"
                        label="Document"
                        mode="single"
                        :options="$page.props.documents ?? []"
                        options-value-prop="id"
                        options-label="number"
                        :searchable="true"
                    />
                </div>
            </Card>

            <Card title="Details">
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                    <TextInput
                        v-model="form.initial_stock_quantity"
                        name="initial_stock_quantity"
                        label="Initial Stock Quantity"
                        type="number"
                    />
                    <TextInput
                        v-model="form.initial_item_cost"
                        name="initial_item_cost"
                        label="Initial Item Cost"
                        type="number"
                    />
                    <TextInput
                        v-model="form.quantity"
                        name="quantity"
                        label="Quantity"
                        type="number"
                    />
                    <TextInput
                        v-model="form.current_stock_quantity"
                        name="current_stock_quantity"
                        label="Current Stock Quantity"
                        type="number"
                    />
                    <TextInput
                        v-model="form.current_item_cost"
                        name="current_item_cost"
                        label="Current Item Cost"
                        type="number"
                    />
                    <div class="sm:col-span-2">
                        <TextInput
                            v-model="form.description"
                            name="description"
                            label="Description"
                            type="text"
                        />
                    </div>
                    <div class="sm:col-span-2">
                        <TextInput
                            v-model="form.comments"
                            name="comments"
                            label="Comments"
                            type="text"
                        />
                    </div>
                </div>
            </Card>

            <Card title="Options">
                <div class="flex flex-wrap gap-x-10 gap-y-4">
                    <Checkbox
                        v-model="form.is_stock_entry"
                        name="is_stock_entry"
                        label="Is Stock Entry"
                    />
                </div>
            </Card>

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
