<template>
    <PageContent>
        <div class="mx-auto max-w-5xl px-1 py-2">
            <div class="grid grid-cols-1 gap-x-14 gap-y-12 lg:grid-cols-3">

                <!-- Main column -->
                <div class="space-y-14 lg:col-span-2">
                    <section>
                        <div :class="headingCls">
                            <h3 :class="titleCls">Item details</h3>
                            <p :class="subCls">Name, code and how it appears in the catalog.</p>
                        </div>
                        <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
                            <TextInput v-model="form.name" name="name" label="Name" type="text" />
                            <TextInput v-model="form.sku_code" name="sku_code" label="SKU Code" type="text" />
                            <div class="sm:col-span-2">
                                <Dropzone v-model="form.images" name="images" label="Images" :max-file-size="5 * 1024 * 1024" />
                            </div>
                            <div class="sm:col-span-2">
                                <TextInput v-model="form.description" name="description" label="Description" type="text" />
                            </div>
                        </div>
                    </section>

                    <section>
                        <div :class="headingCls">
                            <h3 :class="titleCls">Stock management</h3>
                            <p :class="subCls">Quantities and low-stock thresholds. Leave blank for service items.</p>
                        </div>
                        <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-3">
                            <TextInput v-model="form.current_stock_quantity" name="current_stock_quantity" label="Current Stock" type="number" />
                            <TextInput v-model="form.preferred_stock_quantity" name="preferred_stock_quantity" label="Preferred Stock" type="number" />
                            <TextInput v-model="form.min_stock_quantity" name="min_stock_quantity" label="Min Stock" type="number" />
                            <TextInput v-model="form.low_stock_quantity" name="low_stock_quantity" label="Low Stock Alert" type="number" />
                            <TextInput v-model="form.default_quantity" name="default_quantity" label="Default Quantity" type="number" />
                        </div>
                        <div class="mt-8 flex flex-wrap gap-x-10 gap-y-4">
                            <Checkbox v-model="form.using_default_quantity" name="using_default_quantity" label="Using Default Quantity" />
                            <Checkbox v-model="form.low_stock_warning" name="low_stock_warning" label="Low Stock Warning" />
                        </div>
                    </section>

                    <section>
                        <div :class="headingCls">
                            <h3 :class="titleCls">Notes</h3>
                            <p :class="subCls">Internal comments — not shown to customers.</p>
                        </div>
                        <TextInput v-model="form.comments" name="comments" label="Comments" type="text" />
                    </section>
                </div>

                <!-- Side panel -->
                <div class="space-y-14">
                    <section>
                        <div :class="headingCls">
                            <h3 :class="titleCls">Status</h3>
                        </div>
                        <div class="space-y-4">
                            <Checkbox v-model="form.is_active" name="is_active" label="Active" />
                            <Checkbox v-model="form.is_service" name="is_service" label="Service item" />
                            <Checkbox v-model="form.in_stock" name="in_stock" label="In stock" />
                        </div>
                    </section>

                    <section>
                        <div :class="headingCls">
                            <h3 :class="titleCls">Organization</h3>
                            <p :class="subCls">Category, supplier and where it lives.</p>
                        </div>
                        <div class="space-y-6">
                            <Multiselect
                                v-model="form.item_category_id" name="item_category_id" label="Category" mode="single"
                                :options="$page.props.item_categories ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                            <Multiselect
                                v-model="form.supplier_id" name="supplier_id" label="Supplier" mode="single"
                                :options="$page.props.suppliers ?? []" options-value-prop="id" options-label="code" :searchable="true" />
                            <Multiselect
                                v-model="form.measure_unit_id" name="measure_unit_id" label="Measure Unit" mode="single"
                                :options="$page.props.measure_units ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                            <Multiselect
                                v-model="form.store_id" name="store_id" label="Store" mode="single"
                                :options="$page.props.stores ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                        </div>
                    </section>
                </div>

            </div>
        </div>
    </PageContent>
</template>

<script setup lang="ts">
import {
    TextInput,
    PageContent,
    Checkbox,
    Multiselect,
    Dropzone,
} from "craftable-pro/Components";
import { InertiaForm } from "craftable-pro/types";
import type { ItemForm } from "./types";

interface Props {
    form: InertiaForm<ItemForm>;
    submit: void;
}

const props = defineProps<Props>();

const headingCls = "mb-6 border-b border-gray-100 pb-3 dark:border-[#2a2d38]";
const titleCls = "text-lg font-semibold tracking-tight text-gray-900 dark:text-white";
const subCls = "mt-1 text-sm text-gray-400";
</script>
