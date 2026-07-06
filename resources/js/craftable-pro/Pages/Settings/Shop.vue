<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Shop Settings')"
        subtitle="Store-wide defaults for currency, tax and stock behaviour" />

    <PageContent>
        <div class="space-y-6 pb-28">
            <!-- General Settings (wide) -->
            <section class="rounded-2xl border border-gray-200 bg-white dark:border-[#2c2f3d] dark:bg-[#1e2029]">
                <header class="flex items-center gap-2.5 border-b border-gray-100 px-6 py-4 dark:border-[#2a2d38]">
                    <span class="text-lg">⚙️</span>
                    <h3 class="text-[15px] font-semibold text-gray-900 dark:text-white">General Settings</h3>
                </header>

                <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                    <div>
                        <label :class="labelCls">Currency code</label>
                        <input v-model="form.currency_code" type="text" placeholder="MAD" :class="[inputCls, 'uppercase']" />
                        <p class="mt-1.5 text-xs text-gray-400">ISO code — MAD, USD, EUR…</p>
                        <p v-if="form.errors.currency_code" class="mt-1 text-xs text-red-500">{{ form.errors.currency_code }}</p>
                    </div>

                    <div>
                        <label :class="labelCls">Currency symbol</label>
                        <input v-model="form.currency_symbol" type="text" placeholder="DH" :class="inputCls" />
                        <p class="mt-1.5 text-xs text-gray-400">Shown next to every price.</p>
                        <p v-if="form.errors.currency_symbol" class="mt-1 text-xs text-red-500">{{ form.errors.currency_symbol }}</p>
                    </div>

                    <div>
                        <label :class="labelCls">Default tax rate</label>
                        <div class="relative">
                            <input v-model="form.default_tax_rate" type="number" step="0.01" min="0" max="100" :class="[inputCls, 'pr-10']" />
                            <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-sm font-medium text-gray-400">%</span>
                        </div>
                        <p class="mt-1.5 text-xs text-gray-400">Applied to new items unless overridden.</p>
                        <p v-if="form.errors.default_tax_rate" class="mt-1 text-xs text-red-500">{{ form.errors.default_tax_rate }}</p>
                    </div>

                    <div>
                        <label :class="labelCls">Low-stock threshold</label>
                        <input v-model="form.low_stock_threshold" type="number" min="0" :class="inputCls" />
                        <p class="mt-1.5 text-xs text-gray-400">Items at or below this qty are flagged low.</p>
                        <p v-if="form.errors.low_stock_threshold" class="mt-1 text-xs text-red-500">{{ form.errors.low_stock_threshold }}</p>
                    </div>
                </div>
            </section>

            <!-- Setting cards row (Larkon) -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <!-- Currency preview card -->
                <section class="rounded-2xl border border-gray-200 bg-white dark:border-[#2c2f3d] dark:bg-[#1e2029]">
                    <header class="flex items-center gap-2.5 border-b border-gray-100 px-5 py-3.5 dark:border-[#2a2d38]">
                        <span class="text-base">💰</span>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Currency</h3>
                    </header>
                    <div class="p-5">
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl font-bold text-gray-900 dark:text-white">1,250.00</span>
                            <span class="text-lg font-semibold text-primary-500">{{ form.currency_symbol || 'DH' }}</span>
                        </div>
                        <p class="mt-2 text-xs text-gray-400">Live preview using <b class="text-gray-500 dark:text-gray-300">{{ form.currency_code || 'MAD' }}</b>.</p>
                    </div>
                </section>

                <!-- Tax card -->
                <section class="rounded-2xl border border-gray-200 bg-white dark:border-[#2c2f3d] dark:bg-[#1e2029]">
                    <header class="flex items-center gap-2.5 border-b border-gray-100 px-5 py-3.5 dark:border-[#2a2d38]">
                        <span class="text-base">🧾</span>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Tax Settings</h3>
                    </header>
                    <div class="p-5">
                        <label :class="labelCls">Default Tax Rate</label>
                        <div class="relative">
                            <input v-model="form.default_tax_rate" type="number" step="0.01" min="0" max="100" :class="[inputCls, 'pr-10']" />
                            <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-sm font-medium text-gray-400">%</span>
                        </div>
                        <p class="mt-2 text-xs text-gray-400">Standard sales-tax rate for the shop.</p>
                    </div>
                </section>

                <!-- Stock policy card -->
                <section class="rounded-2xl border border-gray-200 bg-white dark:border-[#2c2f3d] dark:bg-[#1e2029]">
                    <header class="flex items-center gap-2.5 border-b border-gray-100 px-5 py-3.5 dark:border-[#2a2d38]">
                        <span class="text-base">📦</span>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Stock Settings</h3>
                    </header>
                    <div class="p-5">
                        <label :class="labelCls">Allow negative stock</label>
                        <div class="mt-1 flex items-center gap-6">
                            <label class="flex cursor-pointer items-center gap-2">
                                <input type="radio" :value="true" v-model="form.negative_stock_allowed" class="h-4 w-4 border-gray-300 text-primary-500 focus:ring-primary-500" />
                                <span class="text-sm text-gray-700 dark:text-gray-200">Yes</span>
                            </label>
                            <label class="flex cursor-pointer items-center gap-2">
                                <input type="radio" :value="false" v-model="form.negative_stock_allowed" class="h-4 w-4 border-gray-300 text-primary-500 focus:ring-primary-500" />
                                <span class="text-sm text-gray-700 dark:text-gray-200">No</span>
                            </label>
                        </div>
                        <p class="mt-3 text-xs text-gray-400">
                            <b>Yes</b> lets sales go below zero stock. <b>No</b> blocks them.
                        </p>
                    </div>
                </section>
            </div>
        </div>
    </PageContent>

    <!-- Footer save bar (Larkon) -->
    <div class="sticky bottom-0 z-10 -mx-4 flex items-center justify-end gap-3 border-t border-gray-200 bg-white/90 px-6 py-4 backdrop-blur dark:border-[#2c2f3d] dark:bg-[#16171d]/90 sm:-mx-6">
        <Button color="gray" variant="outline" :as="Link" :href="route('craftable-pro.home')">
            {{ $t("craftable-pro", "Cancel") }}
        </Button>
        <Button :leftIcon="ArrowDownTrayIcon" @click="submit" :loading="form.processing" v-can="'craftable-pro.settings.edit'">
            {{ $t("craftable-pro", "Save") }}
        </Button>
    </div>
</template>

<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { ArrowDownTrayIcon } from "@heroicons/vue/24/outline";
import { PageHeader, PageContent, Button } from "craftable-pro/Components";
import { useForm } from "craftable-pro/hooks/useForm";

const labelCls = "mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300";
const inputCls = "w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm transition placeholder:text-gray-400 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:border-[#2c2f3d] dark:bg-[#151720] dark:text-white";

interface Props {
    settings: {
        currency_code: string;
        currency_symbol: string;
        default_tax_rate: number;
        negative_stock_allowed: boolean;
        low_stock_threshold: number;
    };
}
const props = defineProps<Props>();

const { form, submit } = useForm(
    {
        currency_code: props.settings.currency_code,
        currency_symbol: props.settings.currency_symbol,
        default_tax_rate: props.settings.default_tax_rate,
        negative_stock_allowed: props.settings.negative_stock_allowed,
        low_stock_threshold: props.settings.low_stock_threshold,
    },
    route("craftable-pro.settings.shop.update"),
    "put"
);
</script>
