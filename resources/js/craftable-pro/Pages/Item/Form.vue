<template>
    <PageContent>
        <div class="mx-auto max-w-3xl px-1 py-2">
            <!-- Stepper -->
            <div class="mb-6 flex items-center">
                <template v-for="(s, i) in steps" :key="s.key">
                    <button type="button" @click="go(i)" class="flex items-center gap-2 focus:outline-none">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-bold transition-all"
                            :class="stepDotCls(i)">
                            <CheckIcon v-if="i < current" class="h-4 w-4" />
                            <span v-else>{{ i + 1 }}</span>
                        </span>
                        <span class="text-sm font-semibold transition-colors"
                            :class="i === current ? 'text-primary-600 dark:text-primary-400' : (i < current ? 'text-gray-700 dark:text-gray-300' : 'text-gray-400')">
                            {{ s.label }}
                        </span>
                    </button>
                    <span v-if="i < steps.length - 1" class="mx-3 h-px flex-1 transition-colors"
                        :class="i < current ? 'bg-primary-400' : 'bg-gray-200 dark:bg-[#2c2f3d]'"></span>
                </template>
            </div>

            <div :class="cardCls">
                <!-- ══════════ STEP 1 · DETAILS ══════════ -->
                <div v-show="current === 0" class="space-y-5 p-6 sm:p-8">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">Item details</h2>

                    <div :class="rowCls">
                        <label :class="labelCls">Name</label>
                        <div class="w-full">
                            <input v-model="form.name" type="text" :class="inputCls" placeholder="e.g. Fresh Milk 1L" />
                        </div>
                    </div>
                    <p v-if="form.errors.name" :class="errCls">{{ form.errors.name }}</p>

                    <div :class="rowCls">
                        <label :class="labelCls">SKU</label>
                        <div class="w-full">
                            <div class="flex items-center gap-2 rounded-xl border border-dashed border-gray-300 px-4 py-2.5 dark:border-[#3a3e4d]">
                                <SparklesIcon class="h-4 w-4 flex-shrink-0 text-primary-500" />
                                <span class="text-sm text-gray-400">{{ form.sku_code || 'Generated automatically on save' }}</span>
                            </div>
                        </div>
                    </div>

                    <div :class="rowCls">
                        <label :class="labelCls">Description</label>
                        <div class="w-full">
                            <input v-model="form.description" type="text" :class="inputCls" placeholder="Short description shown in the catalog" />
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="mb-2 block text-sm font-medium text-gray-600 dark:text-gray-300">Images</label>
                        <Dropzone v-model="form.images" name="images" :max-file-size="5 * 1024 * 1024" />
                    </div>
                </div>

                <!-- ══════════ STEP 2 · STOCK ══════════ -->
                <div v-show="current === 1" class="space-y-6 p-6 sm:p-8">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">Type &amp; stock</h2>

                    <!-- type selection cards -->
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

                <!-- ══════════ STEP 3 · ORGANIZATION ══════════ -->
                <div v-show="current === 2" class="space-y-6 p-6 sm:p-8">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">Organization &amp; status</h2>

                    <!-- review summary -->
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

                <!-- ══════════ FOOTER NAV ══════════ -->
                <div class="flex items-center justify-between border-t border-gray-100 px-6 py-4 dark:border-[#2a2d38]">
                    <button type="button" @click="back" :disabled="current === 0"
                        class="inline-flex items-center gap-1.5 rounded-xl px-4 py-2 text-sm font-medium text-gray-500 transition hover:text-gray-800 disabled:opacity-0 dark:hover:text-gray-200">
                        <ArrowLeftIcon class="h-4 w-4" /> Back
                    </button>

                    <button v-if="current < steps.length - 1" type="button" @click="next"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-primary-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-600">
                        Continue <ArrowRightIcon class="h-4 w-4" />
                    </button>
                    <button v-else type="button" @click="submit" :disabled="form.processing"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-primary-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-600 disabled:opacity-60">
                        <CheckIcon class="h-4 w-4" /> {{ form.processing ? 'Saving…' : 'Save item' }}
                    </button>
                </div>
            </div>
        </div>
    </PageContent>
</template>

<script setup lang="ts">
import { ref, watch } from "vue";
import { PageContent, Checkbox, Multiselect, Dropzone } from "craftable-pro/Components";
import {
    CheckIcon, SparklesIcon, CubeIcon, WrenchScrewdriverIcon,
    ArrowLeftIcon, ArrowRightIcon,
} from "@heroicons/vue/24/outline";
import { InertiaForm } from "craftable-pro/types";
import type { ItemForm } from "./types";

interface Props {
    form: InertiaForm<ItemForm>;
    submit: void;
}
const props = defineProps<Props>();

const steps = [
    { key: "details", label: "Details" },
    { key: "stock", label: "Type & stock" },
    { key: "organization", label: "Organization" },
];
const current = ref(0);

const stockFields = [
    { key: "current_stock_quantity", label: "Current Stock" },
    { key: "preferred_stock_quantity", label: "Preferred Stock" },
    { key: "min_stock_quantity", label: "Min Stock" },
    { key: "low_stock_quantity", label: "Low Stock Alert" },
    { key: "default_quantity", label: "Default Quantity" },
];

// which step each field belongs to — used to jump to the first error on save
const fieldStep: Record<string, number> = {
    name: 0, sku_code: 0, description: 0, images: 0,
    current_stock_quantity: 1, preferred_stock_quantity: 1, min_stock_quantity: 1,
    low_stock_quantity: 1, default_quantity: 1, is_service: 1,
    item_category_id: 2, supplier_id: 2, measure_unit_id: 2, store_id: 2, is_active: 2, in_stock: 2, comments: 2,
};

function go(i: number) { current.value = i; }
function next() { if (current.value < steps.length - 1) current.value++; }
function back() { if (current.value > 0) current.value--; }

// if validation fails on submit, jump to the earliest step that has an error
watch(() => props.form.errors, (errs) => {
    const keys = Object.keys(errs ?? {});
    if (!keys.length) return;
    const target = Math.min(...keys.map((k) => fieldStep[k] ?? 0));
    if (Number.isFinite(target)) current.value = target;
}, { deep: true });

const cardCls = "overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]";
const rowCls = "flex flex-col gap-1 sm:flex-row sm:items-center sm:gap-4";
const labelCls = "w-full text-sm font-medium text-gray-600 dark:text-gray-300 sm:w-32 sm:flex-shrink-0";
const inputCls = "w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 dark:border-[#2c2f3d] dark:bg-[#171923] dark:text-white";
const errCls = "text-xs text-red-500 sm:ml-36";

const stepDotCls = (i: number) =>
    i < current.value ? "bg-primary-500 text-white"
    : i === current.value ? "bg-primary-500 text-white ring-4 ring-primary-500/20"
    : "bg-gray-100 text-gray-400 dark:bg-[#2c2f3d]";

const optCardCls = (active: boolean) =>
    "relative rounded-xl border p-4 text-left transition " +
    (active ? "border-primary-500 bg-primary-500/5 ring-1 ring-primary-500/30"
            : "border-gray-200 hover:border-gray-300 dark:border-[#2c2f3d]");

const radioCls = (active: boolean) =>
    "absolute right-3 top-3 flex h-4 w-4 items-center justify-center rounded-full border " +
    (active ? "border-primary-500 bg-primary-500" : "border-gray-300 dark:border-[#3a3e4d]");
</script>
