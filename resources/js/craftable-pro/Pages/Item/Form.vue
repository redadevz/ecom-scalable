<template>
    <PageContent>
        <div class="mx-auto max-w-5xl space-y-6">

            <!-- Classification -->
            <Card title="Classification">
                <p class="-mt-2 mb-5 text-sm text-gray-500 dark:text-gray-400">
                    Where this item belongs — store, category, supplier and unit of measure.
                </p>
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
                        v-model="form.item_category_id"
                        name="item_category_id"
                        label="Item Category"
                        mode="single"
                        :options="$page.props.item_categories ?? []"
                        options-value-prop="id"
                        options-label="name"
                        :searchable="true"
                    />
                    <Multiselect
                        v-model="form.supplier_id"
                        name="supplier_id"
                        label="Supplier"
                        mode="single"
                        :options="$page.props.suppliers ?? []"
                        options-value-prop="id"
                        options-label="code"
                        :searchable="true"
                    />
                    <Multiselect
                        v-model="form.measure_unit_id"
                        name="measure_unit_id"
                        label="Measure Unit"
                        mode="single"
                        :options="$page.props.measure_units ?? []"
                        options-value-prop="id"
                        options-label="name"
                        :searchable="true"
                    />
                </div>
            </Card>

            <!-- Item details -->
            <Card title="Item details">
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                    <TextInput
                        v-model="form.sku_code"
                        name="sku_code"
                        label="SKU Code"
                        type="text"
                    />
                    <TextInput
                        v-model="form.name"
                        name="name"
                        label="Name"
                        type="text"
                    />
                    <div class="sm:col-span-2">
                        <TextInput
                            v-model="form.description"
                            name="description"
                            label="Description"
                            type="text"
                        />
                    </div>
                </div>
            </Card>

            <!-- Stock management -->
            <Card title="Stock management">
                <p class="-mt-2 mb-5 text-sm text-gray-500 dark:text-gray-400">
                    Quantities and low-stock thresholds. Leave blank for service items.
                </p>
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2 lg:grid-cols-3">
                    <TextInput
                        v-model="form.current_stock_quantity"
                        name="current_stock_quantity"
                        label="Current Stock Quantity"
                        type="number"
                    />
                    <TextInput
                        v-model="form.default_quantity"
                        name="default_quantity"
                        label="Default Quantity"
                        type="number"
                    />
                    <TextInput
                        v-model="form.preferred_stock_quantity"
                        name="preferred_stock_quantity"
                        label="Preferred Stock Quantity"
                        type="number"
                    />
                    <TextInput
                        v-model="form.min_stock_quantity"
                        name="min_stock_quantity"
                        label="Min Stock Quantity"
                        type="number"
                    />
                    <TextInput
                        v-model="form.low_stock_quantity"
                        name="low_stock_quantity"
                        label="Low Stock Quantity"
                        type="number"
                    />
                </div>

                <div class="mt-6 flex flex-wrap gap-x-10 gap-y-4 border-t border-gray-100 pt-5 dark:border-gray-800">
                    <Checkbox v-model="form.is_service" name="is_service" label="Is Service" />
                    <Checkbox v-model="form.in_stock" name="in_stock" label="In Stock" />
                    <Checkbox v-model="form.using_default_quantity" name="using_default_quantity" label="Using Default Quantity" />
                    <Checkbox v-model="form.low_stock_warning" name="low_stock_warning" label="Low Stock Warning" />
                </div>
            </Card>

            <!-- Status & notes -->
            <Card title="Status & notes">
                <div class="space-y-5">
                    <Checkbox v-model="form.is_active" name="is_active" label="Active" />
                    <TextInput
                        v-model="form.comments"
                        name="comments"
                        label="Comments"
                        type="text"
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
import type { ItemForm } from "./types";



interface Props {
    form: InertiaForm<ItemForm>;
    submit: void;

}

const props = defineProps<Props>();

</script>
