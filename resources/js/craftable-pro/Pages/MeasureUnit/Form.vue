<template>
    <PageContent>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- ============ MAIN COLUMN ============ -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Details -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><ScaleIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Details</h3>
                            <p :class="subCls">Name, symbol and description of the measure unit.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <TextInput v-model="form.name" name="name" label="Name" type="text" />
                        <TextInput v-model="form.symbol" name="symbol" label="Symbol" type="text" />
                        <div class="sm:col-span-2">
                            <TextArea v-model="form.description" name="description" label="Description" />
                        </div>
                    </div>
                </section>
            </div>

            <!-- ============ SIDE COLUMN ============ -->
            <div class="space-y-6">
                <div class="lg:sticky lg:top-24 space-y-6">
                    <!-- Live preview -->
                    <section class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]">
                        <div class="relative h-28 bg-gradient-to-br from-primary-500 via-orange-500 to-amber-400">
                            <ScaleIcon class="absolute -right-4 -top-4 h-32 w-32 text-white/15" />
                            <div class="absolute right-3 top-3 rounded-full bg-white/20 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-white backdrop-blur">
                                Measure Unit
                            </div>
                        </div>
                        <div class="px-5 pb-5">
                            <div class="-mt-12 mb-3 flex h-24 w-24 items-center justify-center rounded-2xl border-4 border-white bg-gradient-to-br from-primary-500 to-orange-600 text-3xl font-bold uppercase text-white shadow-lg ring-1 ring-black/5 dark:border-[#1e2029]">
                                {{ initials }}
                            </div>
                            <div class="flex items-center gap-2">
                                <h3 class="truncate text-lg font-semibold text-gray-900 dark:text-white">{{ displayName }}</h3>
                                <span v-if="form.symbol" class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 font-mono text-xs font-medium text-gray-700 dark:bg-white/5 dark:text-gray-200">{{ form.symbol }}</span>
                            </div>
                            <dl class="mt-4 space-y-2 border-t border-gray-100 pt-4 text-sm dark:border-[#2a2d38]">
                                <div v-if="form.description" class="flex items-start gap-2 text-gray-600 dark:text-gray-300">
                                    <PencilSquareIcon class="mt-0.5 h-4 w-4 flex-shrink-0 text-gray-400" /><span>{{ form.description }}</span>
                                </div>
                                <p v-else class="text-xs text-gray-400">A short description appears here as you fill it in.</p>
                            </dl>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </PageContent>
</template>

<script setup lang="ts">
import { computed } from "vue";
import {
    ScaleIcon,
    PencilSquareIcon,
} from "@heroicons/vue/24/outline";
import {
    TextInput,
    TextArea,
    PageContent,
} from "craftable-pro/Components";
import { InertiaForm } from "craftable-pro/types";
import type { MeasureUnitForm } from "./types";

interface Props {
    form: InertiaForm<MeasureUnitForm>;
    submit: void;
}
const props = defineProps<Props>();

const cardCls = "rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]";
const headerCls = "flex items-center gap-3 border-b border-gray-100 px-6 py-4 dark:border-[#2a2d38]";
const iconBadge = "flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-primary-500/10 text-primary-500";
const titleCls = "text-[15px] font-semibold text-gray-900 dark:text-white";
const subCls = "text-xs text-gray-400";

const displayName = computed(() => props.form.name || "New Measure Unit");

const initials = computed(() => {
    const base = (props.form.symbol || props.form.name || "").toString().trim();
    if (!base) return "MU";
    const parts = base.split(/\s+/).filter(Boolean);
    return (parts[0][0] + (parts[1]?.[0] ?? "")).toUpperCase();
});
</script>
