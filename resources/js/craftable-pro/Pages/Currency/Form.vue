<template>
    <PageContent>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- ============ MAIN COLUMN ============ -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Details -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><BanknotesIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Details</h3>
                            <p :class="subCls">Name, short name and symbol of the currency.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <TextInput v-model="form.name" name="name" label="Name" type="text" />
                        <TextInput v-model="form.short_name" name="short_name" label="Short Name" type="text" />
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
                            <BanknotesIcon class="absolute -right-4 -top-4 h-32 w-32 text-white/15" />
                            <div class="absolute right-3 top-3 rounded-full bg-white/20 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-white backdrop-blur">
                                {{ form.short_name || 'Currency' }}
                            </div>
                        </div>
                        <div class="px-5 pb-5">
                            <div class="-mt-12 mb-3 flex h-24 w-24 items-center justify-center rounded-2xl border-4 border-white bg-gradient-to-br from-primary-500 to-orange-600 text-3xl font-bold uppercase text-white shadow-lg ring-1 ring-black/5 dark:border-[#1e2029]">
                                {{ initials }}
                            </div>
                            <h3 class="truncate text-lg font-semibold text-gray-900 dark:text-white">{{ displayName }}</h3>
                            <p class="text-sm text-gray-400">{{ form.symbol || '—' }}</p>

                            <dl class="mt-4 space-y-2 border-t border-gray-100 pt-4 text-sm dark:border-[#2a2d38]">
                                <p v-if="form.description" class="text-gray-600 dark:text-gray-300">{{ form.description }}</p>
                                <p v-else class="text-xs text-gray-400">Description appears here as you fill it in.</p>
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
import { BanknotesIcon } from "@heroicons/vue/24/outline";
import {
    TextInput,
    TextArea,
    PageContent,
} from "craftable-pro/Components";
import { InertiaForm } from "craftable-pro/types";
import type { CurrencyForm } from "./types";

interface Props {
    form: InertiaForm<CurrencyForm>;
    submit: void;
}
const props = defineProps<Props>();

const cardCls = "rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]";
const headerCls = "flex items-center gap-3 border-b border-gray-100 px-6 py-4 dark:border-[#2a2d38]";
const iconBadge = "flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-primary-500/10 text-primary-500";
const titleCls = "text-[15px] font-semibold text-gray-900 dark:text-white";
const subCls = "text-xs text-gray-400";

const displayName = computed(() => props.form.name || "New Currency");

const initials = computed(() => {
    const symbol = (props.form.symbol || "").toString().trim();
    if (symbol) return symbol.slice(0, 2);
    const base = (props.form.short_name || props.form.name || "").toString().trim();
    return base ? base.slice(0, 2).toUpperCase() : "C";
});
</script>
