<template>
    <Wizard :steps="steps" :processing="form.processing" :errors="form.errors" :field-step="fieldStep"
        submit-label="Save price" max-width="3xl" @submit="submit">

        <!-- STEP 1 · PRICING -->
        <template #pricing>
            <div class="space-y-5 p-6 sm:p-8">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Pricing</h2>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-600 dark:text-gray-300">Item</label>
                    <Multiselect v-model="form.item_id" name="item_id" mode="single"
                        :options="$page.props.items ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                    <p v-if="form.errors.item_id" class="mt-1 text-xs text-red-500">{{ form.errors.item_id }}</p>
                </div>

                <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                    <div v-for="f in priceFields" :key="f.key">
                        <label class="mb-1 block text-sm font-medium text-gray-600 dark:text-gray-300">{{ f.label }}</label>
                        <input v-model="(form as any)[f.key]" type="number" step="0.01" :class="inputCls" placeholder="0.00" />
                        <p v-if="form.errors[f.key]" class="mt-1 text-xs text-red-500">{{ form.errors[f.key] }}</p>
                    </div>
                </div>
            </div>
        </template>

        <!-- STEP 2 · SCHEDULE -->
        <template #schedule>
            <div class="space-y-5 p-6 sm:p-8">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Schedule</h2>
                <p class="text-sm text-gray-400">When this price is valid. Leave blank for “always”.</p>
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-600 dark:text-gray-300">Start time</label>
                        <DatePicker v-model="form.start_time" name="start_time" mode="dateTime" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-600 dark:text-gray-300">End time</label>
                        <DatePicker v-model="form.end_time" name="end_time" mode="dateTime" />
                    </div>
                </div>
            </div>
        </template>

        <!-- STEP 3 · SETTINGS -->
        <template #settings>
            <div class="space-y-6 p-6 sm:p-8">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Settings</h2>

                <div class="space-y-2 rounded-xl bg-gray-50 p-4 dark:bg-[#171923]">
                    <div class="flex items-center justify-between text-sm"><span class="text-gray-400">Sale price</span><span class="font-semibold text-gray-900 dark:text-white">{{ form.sale_price || '—' }}</span></div>
                    <div class="flex items-center justify-between text-sm"><span class="text-gray-400">Markup</span><span class="font-medium text-gray-900 dark:text-white">{{ form.markup_percentage ? form.markup_percentage + '%' : '—' }}</span></div>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-600 dark:text-gray-300">Store</label>
                        <Multiselect v-model="form.store_id" name="store_id" mode="single"
                            :options="$page.props.stores ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-600 dark:text-gray-300">Created by</label>
                        <Multiselect v-model="form.created_by" name="created_by" mode="single"
                            :options="$page.props.craftable_pro_users ?? []" options-value-prop="id" options-label="email" :searchable="true" />
                    </div>
                </div>

                <div class="divide-y divide-gray-100 rounded-xl border border-gray-200 dark:divide-[#2a2d38] dark:border-[#2c2f3d]">
                    <Toggle v-model="form.is_active" title="Active" desc="Inactive prices are hidden from operations." />
                    <Toggle v-model="form.price_change_allowed" title="Price change allowed" desc="Allow manual overrides of this price." />
                </div>

                <Field v-model="form.description" label="Description" :error="form.errors.description" />
                <Field v-model="form.comments" label="Comments" :error="form.errors.comments" placeholder="Internal notes" />
            </div>
        </template>
    </Wizard>
</template>

<script setup lang="ts">
import { h } from "vue";
import { DatePicker, Multiselect } from "craftable-pro/Components";
import Wizard from "@/craftable-pro/Components/Wizard.vue";
import { InertiaForm } from "craftable-pro/types";
import type { PriceForm } from "./types";

interface Props {
    form: InertiaForm<PriceForm>;
    submit: () => void;
}
defineProps<Props>();

const steps = [
    { key: "pricing", label: "Pricing" },
    { key: "schedule", label: "Schedule" },
    { key: "settings", label: "Settings" },
];

const priceFields = [
    { key: "current_item_cost", label: "Current cost" },
    { key: "markup_percentage", label: "Markup %" },
    { key: "price_before_tax", label: "Price before tax" },
    { key: "tax_value", label: "Tax value" },
    { key: "price_after_tax", label: "Price after tax" },
    { key: "sale_price", label: "Sale price" },
];

const fieldStep: Record<string, number> = {
    item_id: 0, current_item_cost: 0, markup_percentage: 0, price_before_tax: 0, tax_value: 0, price_after_tax: 0, sale_price: 0,
    start_time: 1, end_time: 1,
    store_id: 2, created_by: 2, is_active: 2, price_change_allowed: 2, description: 2, comments: 2,
};

const rowCls = "flex flex-col gap-1 sm:flex-row sm:items-center sm:gap-4";
const labelCls = "w-full text-sm font-medium text-gray-600 dark:text-gray-300 sm:w-32 sm:flex-shrink-0";
const inputCls = "w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 dark:border-[#2c2f3d] dark:bg-[#171923] dark:text-white";

const Field = (p: any, { emit }: any) =>
    h("div", { class: rowCls }, [
        h("label", { class: labelCls }, p.label),
        h("div", { class: "w-full" }, [
            h("input", { value: p.modelValue, type: p.type || "text", placeholder: p.placeholder, class: inputCls, onInput: (e: any) => emit("update:modelValue", e.target.value) }),
            p.error ? h("p", { class: "mt-1 text-xs text-red-500" }, p.error) : null,
        ]),
    ]);
(Field as any).props = ["modelValue", "label", "error", "type", "placeholder"];
(Field as any).emits = ["update:modelValue"];

const Toggle = (p: any, { emit }: any) =>
    h("div", { class: "flex items-center justify-between gap-4 p-4" }, [
        h("div", {}, [
            h("p", { class: "text-sm font-medium text-gray-900 dark:text-white" }, p.title),
            h("p", { class: "text-xs text-gray-400" }, p.desc),
        ]),
        h("button", {
            type: "button",
            onClick: () => emit("update:modelValue", !p.modelValue),
            class: ["relative inline-flex h-6 w-11 flex-shrink-0 items-center rounded-full transition-colors focus:outline-none", p.modelValue ? "bg-primary-500" : "bg-gray-300 dark:bg-[#3a3d4d]"],
        }, [h("span", { class: ["inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform", p.modelValue ? "translate-x-6" : "translate-x-1"] })]),
    ]);
(Toggle as any).props = ["modelValue", "title", "desc"];
(Toggle as any).emits = ["update:modelValue"];
</script>
