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
                                v-model="form.item_category_id"
                                name="item_category_id"
                                :label="$t('craftable-pro', 'Item Category')"
                                mode="single"
                                :options="$page.props.item_categories ?? []"
                                options-value-prop="id"
                                options-label="name"
                                :searchable="true"
                            />
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
                            <Multiselect
                                v-model="form.measure_unit_id"
                                name="measure_unit_id"
                                :label="$t('craftable-pro', 'Measure Unit')"
                                mode="single"
                                :options="$page.props.measure_units ?? []"
                                options-value-prop="id"
                                options-label="name"
                                :searchable="true"
                            />
                            <TextInput
                                v-model="form.sku_code"
                                name="sku_code"
                                :label="$t('craftable-pro', 'Sku Code')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.name"
                                name="name"
                                :label="$t('craftable-pro', 'Name')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.description"
                                name="description"
                                :label="$t('craftable-pro', 'Description')"
                                type="text"
                            />
                            <Checkbox
                                v-model="form.is_service"
                                name="is_service"
                                :label="$t('craftable-pro', 'Is Service')"
                            />
                            <Checkbox
                                v-model="form.in_stock"
                                name="in_stock"
                                :label="$t('craftable-pro', 'In Stock')"
                            />
                            <Checkbox
                                v-model="form.using_default_quantity"
                                name="using_default_quantity"
                                :label="$t('craftable-pro', 'Using Default Quantity')"
                            />
                            <TextInput
                                v-model="form.default_quantity"
                                name="default_quantity"
                                :label="$t('craftable-pro', 'Default Quantity')"
                                type="number"
                            />
                            <TextInput
                                v-model="form.current_stock_quantity"
                                name="current_stock_quantity"
                                :label="$t('craftable-pro', 'Current Stock Quantity')"
                                type="number"
                            />
                            <TextInput
                                v-model="form.preferred_stock_quantity"
                                name="preferred_stock_quantity"
                                :label="$t('craftable-pro', 'Preferred Stock Quantity')"
                                type="number"
                            />
                            <TextInput
                                v-model="form.min_stock_quantity"
                                name="min_stock_quantity"
                                :label="$t('craftable-pro', 'Min Stock Quantity')"
                                type="number"
                            />
                            <Checkbox
                                v-model="form.low_stock_warning"
                                name="low_stock_warning"
                                :label="$t('craftable-pro', 'Low Stock Warning')"
                            />
                            <TextInput
                                v-model="form.low_stock_quantity"
                                name="low_stock_quantity"
                                :label="$t('craftable-pro', 'Low Stock Quantity')"
                                type="number"
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
import type { ItemForm } from "./types";



interface Props {
    form: InertiaForm<ItemForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
