<template>
    <Wizard :steps="steps" :processing="form.processing" :errors="form.errors" :field-step="fieldStep"
        submit-label="Save bar code" max-width="2xl" @submit="submit">

        <!-- STEP 1 · DETAILS -->
        <template #details>
            <div class="space-y-5 p-6 sm:p-8">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Bar code details</h2>

                <div :class="rowCls">
                    <label :class="labelCls">Bar code</label>
                    <div class="w-full">
                        <input v-model="form.bar_code" type="text" :class="inputCls" placeholder="Scan or type the code" />
                        <p v-if="form.errors.bar_code" class="mt-1 text-xs text-red-500">{{ form.errors.bar_code }}</p>
                    </div>
                </div>

                <div :class="rowCls">
                    <label :class="labelCls">Description</label>
                    <div class="w-full">
                        <input v-model="form.description" type="text" :class="inputCls" placeholder="Optional label" />
                    </div>
                </div>
            </div>
        </template>

        <!-- STEP 2 · SETTINGS -->
        <template #settings>
            <div class="space-y-6 p-6 sm:p-8">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Settings</h2>

                <div class="space-y-2 rounded-xl bg-gray-50 p-4 dark:bg-[#171923]">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-400">Bar code</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ form.bar_code || '—' }}</span>
                    </div>
                </div>

                <div :class="rowCls">
                    <label :class="labelCls">Item</label>
                    <div class="w-full">
                        <Multiselect v-model="form.item_id" name="item_id" mode="single"
                            :options="$page.props.items ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                    </div>
                </div>

                <div class="flex items-center justify-between gap-4 rounded-xl border border-gray-200 p-4 dark:border-[#2c2f3d]">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Active</p>
                        <p class="text-xs text-gray-400">Inactive bar codes are hidden from operations.</p>
                    </div>
                    <button type="button" @click="form.is_active = !form.is_active"
                        class="relative inline-flex h-6 w-11 flex-shrink-0 items-center rounded-full transition-colors focus:outline-none"
                        :class="form.is_active ? 'bg-primary-500' : 'bg-gray-300 dark:bg-[#3a3d4d]'">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform" :class="form.is_active ? 'translate-x-6' : 'translate-x-1'"></span>
                    </button>
                </div>
            </div>
        </template>
    </Wizard>
</template>

<script setup lang="ts">
import { Multiselect } from "craftable-pro/Components";
import Wizard from "@/craftable-pro/Components/Wizard.vue";
import { InertiaForm } from "craftable-pro/types";
import type { BarCodeForm } from "./types";

interface Props {
    form: InertiaForm<BarCodeForm>;
    submit: () => void;
}
defineProps<Props>();

const steps = [
    { key: "details", label: "Details" },
    { key: "settings", label: "Settings" },
];
const fieldStep: Record<string, number> = { bar_code: 0, description: 0, item_id: 1, is_active: 1 };

const rowCls = "flex flex-col gap-1 sm:flex-row sm:items-center sm:gap-4";
const labelCls = "w-full text-sm font-medium text-gray-600 dark:text-gray-300 sm:w-32 sm:flex-shrink-0";
const inputCls = "w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 dark:border-[#2c2f3d] dark:bg-[#171923] dark:text-white";
</script>
