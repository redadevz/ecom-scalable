<template>
    <Wizard :steps="steps" :processing="form.processing" :errors="form.errors" :field-step="fieldStep"
        submit-label="Save item" max-width="3xl" @submit="submit">

        <!-- STEP 1 · DETAILS -->
        <template #details>
            <div class="space-y-5 p-6 sm:p-8">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Item details</h2>

                <div :class="rowCls">
                    <label :class="labelCls">Name</label>
                    <div class="w-full"><input v-model="form.name" type="text" :class="inputCls" placeholder="e.g. Fresh Milk 1L" /></div>
                </div>
                <p v-if="form.errors.name" :class="errCls">{{ form.errors.name }}</p>

                <div :class="rowCls">
                    <label :class="labelCls">SKU</label>
                    <div class="w-full">
                        <input :value="form.sku_code" readonly placeholder="Generated on save" :class="codeCls" />
                    </div>
                </div>

                <div :class="rowCls">
                    <label :class="labelCls">Description</label>
                    <div class="w-full"><input v-model="form.description" type="text" :class="inputCls" placeholder="Short description shown in the catalog" /></div>
                </div>

                <div class="pt-2">
                    <label class="mb-2 block text-sm font-medium text-gray-600 dark:text-gray-300">Images</label>
                    <Dropzone v-model="form.images" name="images" :max-file-size="5 * 1024 * 1024" />
                </div>
            </div>
        </template>

        <!-- STEP 2 · TYPE & STOCK -->
        <template #stock>
            <div class="space-y-6 p-6 sm:p-8">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Type &amp; stock</h2>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <button type="button" @click="form.is_service = false" :class="optCardCls(!form.is_service)">
                        <span :class="radioCls(!form.is_service)"><span v-if="!form.is_service" class="h-2 w-2 rounded-full bg-white"></span></span>
                        <CubeIcon class="h-5 w-5 text-primary-500" />
                        <span class="mt-1 block text-sm font-semibold text-gray-900 dark:text-white">Physical product</span>
                        <span class="block text-xs text-gray-400">Tracks stock quantity.</span>
                    </button>
                    <button type="button" @click="form.is_service = true" :class="optCardCls(form.is_service)">
                        <span :class="radioCls(form.is_service)"><span v-if="form.is_service" class="h-2 w-2 rounded-full bg-white"></span></span>
                        <WrenchScrewdriverIcon class="h-5 w-5 text-primary-500" />
                        <span class="mt-1 block text-sm font-semibold text-gray-900 dark:text-white">Service</span>
                        <span class="block text-xs text-gray-400">No stock tracking.</span>
                    </button>
                </div>

                <template v-if="!form.is_service">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                        <div v-for="f in stockFields" :key="f.key">
                            <label class="mb-1 block text-sm font-medium text-gray-600 dark:text-gray-300">{{ f.label }}</label>
                            <input v-model="(form as any)[f.key]" type="number" :class="inputCls" placeholder="0" />
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-x-8 gap-y-3 border-t border-gray-100 pt-5 dark:border-[#2a2d38]">
                        <Checkbox v-model="form.using_default_quantity" name="using_default_quantity" label="Using Default Quantity" />
                        <Checkbox v-model="form.low_stock_warning" name="low_stock_warning" label="Low Stock Warning" />
                    </div>
                </template>
                <p v-else class="rounded-xl bg-gray-50 px-4 py-6 text-center text-sm text-gray-400 dark:bg-[#171923]">
                    Services don’t hold stock — nothing to set here.
                </p>
            </div>
        </template>

        <!-- STEP 3 · ORGANIZATION -->
        <template #organization>
            <div class="space-y-6 p-6 sm:p-8">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Organization &amp; status</h2>

                <div class="space-y-2 rounded-xl bg-gray-50 p-4 dark:bg-[#171923]">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-400">Name</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ form.name || '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-400">Type</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ form.is_service ? 'Service' : 'Physical product' }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <Multiselect v-model="form.item_category_id" name="item_category_id" label="Category" mode="single"
                        :options="$page.props.item_categories ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                    <Multiselect v-model="form.supplier_id" name="supplier_id" label="Supplier" mode="single"
                        :options="$page.props.suppliers ?? []" options-value-prop="id" options-label="code" :searchable="true" />
                    <Multiselect v-model="form.measure_unit_id" name="measure_unit_id" label="Measure Unit" mode="single"
                        :options="$page.props.measure_units ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                    <Multiselect v-model="form.store_id" name="store_id" label="Store" mode="single"
                        :options="$page.props.stores ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                </div>

                <div class="flex flex-wrap gap-x-8 gap-y-3 border-t border-gray-100 pt-5 dark:border-[#2a2d38]">
                    <Checkbox v-model="form.is_active" name="is_active" label="Active" />
                    <Checkbox v-model="form.in_stock" name="in_stock" label="In stock" />
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-600 dark:text-gray-300">Internal notes</label>
                    <input v-model="form.comments" type="text" :class="inputCls" placeholder="Not shown to customers" />
                </div>
            </div>
        </template>
    </Wizard>
</template>

<script setup lang="ts">
import { Checkbox, Multiselect, Dropzone } from "craftable-pro/Components";
import { CubeIcon, WrenchScrewdriverIcon } from "@heroicons/vue/24/outline";
import Wizard from "@/craftable-pro/Components/Wizard.vue";
import { InertiaForm } from "craftable-pro/types";
import type { ItemForm } from "./types";

interface Props {
    form: InertiaForm<ItemForm>;
    submit: () => void;
}
defineProps<Props>();

const steps = [
    { key: "details", label: "Details" },
    { key: "stock", label: "Type & stock" },
    { key: "organization", label: "Organization" },
];

const stockFields = [
    { key: "current_stock_quantity", label: "Current Stock" },
    { key: "preferred_stock_quantity", label: "Preferred Stock" },
    { key: "min_stock_quantity", label: "Min Stock" },
    { key: "low_stock_quantity", label: "Low Stock Alert" },
    { key: "default_quantity", label: "Default Quantity" },
];

const fieldStep: Record<string, number> = {
    name: 0, sku_code: 0, description: 0, images: 0,
    current_stock_quantity: 1, preferred_stock_quantity: 1, min_stock_quantity: 1,
    low_stock_quantity: 1, default_quantity: 1, is_service: 1,
    item_category_id: 2, supplier_id: 2, measure_unit_id: 2, store_id: 2, is_active: 2, in_stock: 2, comments: 2,
};

const rowCls = "flex flex-col gap-1 sm:flex-row sm:items-center sm:gap-4";
const labelCls = "w-full text-sm font-medium text-gray-600 dark:text-gray-300 sm:w-32 sm:flex-shrink-0";
const inputCls = "w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 dark:border-[#2c2f3d] dark:bg-[#171923] dark:text-white";
const errCls = "text-xs text-red-500 sm:ml-36";
const codeCls = "w-full cursor-not-allowed select-all rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium text-gray-700 focus:outline-none dark:border-[#2c2f3d] dark:bg-[#1a1c27] dark:text-gray-200";

const optCardCls = (active: boolean) =>
    "relative rounded-xl border p-4 text-left transition " +
    (active ? "border-primary-500 bg-primary-500/5 ring-1 ring-primary-500/30" : "border-gray-200 hover:border-gray-300 dark:border-[#2c2f3d]");

const radioCls = (active: boolean) =>
    "absolute right-3 top-3 flex h-4 w-4 items-center justify-center rounded-full border " +
    (active ? "border-primary-500 bg-primary-500" : "border-gray-300 dark:border-[#3a3e4d]");
</script>
