<template>
    <PageContent>
        <div class="mx-auto max-w-2xl px-1 py-2">
            <!-- Stepper -->
            <div class="mb-6 flex items-center">
                <template v-for="(s, i) in steps" :key="s.key">
                    <button type="button" @click="go(i)" class="flex items-center gap-2 focus:outline-none">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-bold transition-all" :class="stepDotCls(i)">
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
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">Category details</h2>

                    <div :class="rowCls">
                        <label :class="labelCls">Name</label>
                        <div class="w-full">
                            <input v-model="form.name" type="text" :class="inputCls" placeholder="e.g. Beverages" />
                        </div>
                    </div>
                    <p v-if="form.errors.name" :class="errCls">{{ form.errors.name }}</p>

                    <div class="flex flex-col gap-1 sm:flex-row sm:gap-4">
                        <label :class="labelCls + ' sm:pt-2'">Description</label>
                        <div class="w-full">
                            <textarea v-model="form.description" rows="4" :class="inputCls" placeholder="Optional — what belongs in this category"></textarea>
                        </div>
                    </div>
                </div>

                <!-- ══════════ STEP 2 · SETTINGS ══════════ -->
                <div v-show="current === 1" class="space-y-6 p-6 sm:p-8">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">Settings</h2>

                    <div class="space-y-2 rounded-xl bg-gray-50 p-4 dark:bg-[#171923]">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-400">Name</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ form.name || '—' }}</span>
                        </div>
                    </div>

                    <Multiselect v-model="form.parent_category_id" name="parent_category_id" label="Parent category" mode="single"
                        :options="$page.props.item_categories ?? []" options-value-prop="id" options-label="name" :searchable="true" />

                    <!-- Active toggle -->
                    <div class="flex items-center justify-between gap-4 rounded-xl border border-gray-200 p-4 dark:border-[#2c2f3d]">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Active</p>
                            <p class="text-xs text-gray-400">Inactive categories are hidden from operations.</p>
                        </div>
                        <button type="button" @click="form.is_active = !form.is_active"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 items-center rounded-full transition-colors focus:outline-none"
                            :class="form.is_active ? 'bg-primary-500' : 'bg-gray-300 dark:bg-[#3a3d4d]'">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform" :class="form.is_active ? 'translate-x-6' : 'translate-x-1'"></span>
                        </button>
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
                        <CheckIcon class="h-4 w-4" /> {{ form.processing ? 'Saving…' : 'Save category' }}
                    </button>
                </div>
            </div>
        </div>
    </PageContent>
</template>

<script setup lang="ts">
import { ref, watch } from "vue";
import { PageContent, Multiselect } from "craftable-pro/Components";
import { CheckIcon, ArrowLeftIcon, ArrowRightIcon } from "@heroicons/vue/24/outline";
import { InertiaForm } from "craftable-pro/types";
import type { ItemCategoryForm } from "./types";

interface Props {
    form: InertiaForm<ItemCategoryForm>;
    submit: void;
}
const props = defineProps<Props>();

const steps = [
    { key: "details", label: "Details" },
    { key: "settings", label: "Settings" },
];
const current = ref(0);

const fieldStep: Record<string, number> = { name: 0, description: 0, parent_category_id: 1, is_active: 1 };

function go(i: number) { current.value = i; }
function next() { if (current.value < steps.length - 1) current.value++; }
function back() { if (current.value > 0) current.value--; }

watch(() => props.form.errors, (errs) => {
    const keys = Object.keys(errs ?? {});
    if (!keys.length) return;
    current.value = Math.min(...keys.map((k) => fieldStep[k] ?? 0));
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
</script>
