<template>
    <PageHeader sticky :title="$t('craftable-pro', 'Shop Settings')">
        <Button :leftIcon="ArrowDownTrayIcon" @click="submit" :loading="form.processing" v-can="'craftable-pro.settings.edit'">
            {{ $t("craftable-pro", "Save") }}
        </Button>
    </PageHeader>

    <PageContent>
        <div class="mx-auto max-w-6xl space-y-8">
            <!-- Intro -->
            <div class="flex items-start gap-4">
                <span class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-primary-500 to-orange-600 text-white shadow-lg shadow-primary-500/25">
                    <BuildingStorefrontIcon class="h-6 w-6" />
                </span>
                <div class="pt-0.5">
                    <h2 class="text-lg font-semibold tracking-tight text-gray-900 dark:text-white">Store configuration</h2>
                    <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">Global defaults for currency, tax and how stock behaves across every screen.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
                <!-- MAIN -->
                <div class="space-y-8 lg:col-span-7">
                    <!-- Currency & Tax -->
                    <section :class="cardCls">
                        <header :class="headerCls">
                            <span :class="iconBadge"><BanknotesIcon class="h-5 w-5" /></span>
                            <div>
                                <h3 :class="titleCls">Currency &amp; Tax</h3>
                                <p :class="subCls">How money and tax are displayed and calculated.</p>
                            </div>
                        </header>
                        <div class="space-y-6 p-6 sm:p-7">
                            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <label :class="labelCls">Currency code</label>
                                    <input v-model="form.currency_code" type="text" maxlength="3" placeholder="MAD" :class="[inputCls, 'font-medium uppercase tracking-wide']" />
                                    <p :class="hintCls">ISO 4217 — MAD, USD, EUR…</p>
                                    <p v-if="form.errors.currency_code" :class="errCls">{{ form.errors.currency_code }}</p>
                                </div>
                                <div>
                                    <label :class="labelCls">Currency symbol</label>
                                    <input v-model="form.currency_symbol" type="text" maxlength="8" placeholder="DH" :class="inputCls" />
                                    <p :class="hintCls">Shown next to every price.</p>
                                    <p v-if="form.errors.currency_symbol" :class="errCls">{{ form.errors.currency_symbol }}</p>
                                </div>
                            </div>

                            <div class="border-t border-gray-100 pt-6 dark:border-[#2a2d38]">
                                <label :class="labelCls">Default tax rate</label>
                                <div class="relative max-w-[12rem]">
                                    <input v-model="form.default_tax_rate" type="number" step="0.01" min="0" max="100" :class="[inputCls, 'pr-11 text-right font-semibold tabular-nums']" />
                                    <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-sm font-medium text-gray-400">%</span>
                                </div>
                                <p :class="hintCls">Applied to new items unless overridden per item.</p>
                                <p v-if="form.errors.default_tax_rate" :class="errCls">{{ form.errors.default_tax_rate }}</p>
                            </div>
                        </div>
                    </section>

                    <!-- Inventory & Stock -->
                    <section :class="cardCls">
                        <header :class="headerCls">
                            <span :class="iconBadge"><CubeIcon class="h-5 w-5" /></span>
                            <div>
                                <h3 :class="titleCls">Inventory &amp; Stock</h3>
                                <p :class="subCls">Low-stock alerts and how sales behave without stock.</p>
                            </div>
                        </header>
                        <div class="space-y-6 p-6 sm:p-7">
                            <div>
                                <label :class="labelCls">Low-stock threshold</label>
                                <div class="relative max-w-[12rem]">
                                    <input v-model="form.low_stock_threshold" type="number" min="0" :class="[inputCls, 'pr-16 text-right font-semibold tabular-nums']" />
                                    <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-sm font-medium text-gray-400">units</span>
                                </div>
                                <p :class="hintCls">Items at or below this quantity are flagged as low.</p>
                                <p v-if="form.errors.low_stock_threshold" :class="errCls">{{ form.errors.low_stock_threshold }}</p>
                            </div>

                            <button type="button" @click="form.negative_stock_allowed = !form.negative_stock_allowed"
                                class="flex w-full items-center justify-between gap-6 rounded-xl border border-gray-100 bg-gray-50/70 p-4 text-left transition hover:border-gray-200 dark:border-[#2a2d38] dark:bg-white/5 dark:hover:border-[#3a3d4d]">
                                <div class="flex items-start gap-3">
                                    <span class="mt-0.5 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg"
                                        :class="form.negative_stock_allowed ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400' : 'bg-green-500/10 text-green-600 dark:text-green-400'">
                                        <ExclamationTriangleIcon v-if="form.negative_stock_allowed" class="h-4 w-4" />
                                        <ShieldCheckIcon v-else class="h-4 w-4" />
                                    </span>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Allow negative stock</p>
                                        <p class="mt-0.5 text-xs leading-relaxed text-gray-500 dark:text-gray-400">
                                            When <b>on</b>, a sale can proceed even without enough stock. When <b>off</b>, it's blocked.
                                        </p>
                                    </div>
                                </div>
                                <span :class="form.negative_stock_allowed ? 'bg-primary-500' : 'bg-gray-300 dark:bg-[#3a3d4d]'"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 items-center rounded-full transition-colors">
                                    <span :class="form.negative_stock_allowed ? 'translate-x-6' : 'translate-x-1'" class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform" />
                                </span>
                            </button>
                        </div>
                    </section>
                </div>

                <!-- SIDE -->
                <div class="lg:col-span-5">
                    <div class="space-y-6 lg:sticky lg:top-24">
                        <!-- Live preview -->
                        <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]">
                            <div class="flex items-center gap-2 border-b border-gray-100 bg-gray-50/70 px-5 py-3 dark:border-[#2a2d38] dark:bg-white/5">
                                <EyeIcon class="h-4 w-4 text-gray-400" />
                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Live preview</span>
                            </div>
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-500/10 text-primary-500"><CubeIcon class="h-5 w-5" /></span>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">Sample item</p>
                                            <p class="text-xs text-gray-400">1 × unit price</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-medium tabular-nums text-gray-500 dark:text-gray-400">{{ money(sample) }}</span>
                                </div>

                                <dl class="mt-5 space-y-2.5 text-sm">
                                    <div class="flex justify-between text-gray-500 dark:text-gray-400"><dt>Subtotal</dt><dd class="tabular-nums">{{ money(sample) }}</dd></div>
                                    <div class="flex justify-between text-gray-500 dark:text-gray-400"><dt>Tax ({{ form.default_tax_rate ?? 0 }}%)</dt><dd class="tabular-nums">+ {{ money(taxAmount) }}</dd></div>
                                </dl>

                                <div class="mt-4 flex items-baseline justify-between rounded-xl bg-gray-50 px-4 py-3.5 dark:bg-white/5">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">Total</span>
                                    <span class="text-2xl font-bold tabular-nums text-primary-600 dark:text-primary-400">{{ money(total) }}</span>
                                </div>
                                <p class="mt-3 text-center text-xs text-gray-400">As the customer sees it, in <b class="text-gray-500 dark:text-gray-300">{{ form.currency_code || 'MAD' }}</b></p>
                            </div>
                        </section>

                        <!-- Policies summary -->
                        <section :class="cardCls">
                            <header :class="headerCls">
                                <span :class="iconBadge"><ShieldCheckIcon class="h-5 w-5" /></span>
                                <h3 :class="titleCls">Current policy</h3>
                            </header>
                            <dl class="divide-y divide-gray-100 dark:divide-[#2a2d38]">
                                <div class="flex items-center justify-between px-6 py-3.5 text-sm">
                                    <dt class="text-gray-500 dark:text-gray-400">Negative stock</dt>
                                    <dd>
                                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-semibold"
                                            :class="form.negative_stock_allowed ? 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400' : 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400'">
                                            <span class="h-1.5 w-1.5 rounded-full" :class="form.negative_stock_allowed ? 'bg-amber-500' : 'bg-green-500'"></span>
                                            {{ form.negative_stock_allowed ? 'Allowed' : 'Blocked' }}
                                        </span>
                                    </dd>
                                </div>
                                <div class="flex items-center justify-between px-6 py-3.5 text-sm">
                                    <dt class="text-gray-500 dark:text-gray-400">Low-stock alert</dt>
                                    <dd class="font-medium tabular-nums text-gray-900 dark:text-white">≤ {{ form.low_stock_threshold ?? 0 }} units</dd>
                                </div>
                                <div class="flex items-center justify-between px-6 py-3.5 text-sm">
                                    <dt class="text-gray-500 dark:text-gray-400">Default tax</dt>
                                    <dd class="font-medium tabular-nums text-gray-900 dark:text-white">{{ form.default_tax_rate ?? 0 }}%</dd>
                                </div>
                            </dl>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </PageContent>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { ArrowDownTrayIcon, BanknotesIcon, CubeIcon, EyeIcon, ShieldCheckIcon, BuildingStorefrontIcon, ExclamationTriangleIcon } from "@heroicons/vue/24/outline";
import { PageHeader, PageContent, Button } from "craftable-pro/Components";
import { useForm } from "craftable-pro/hooks/useForm";

const labelCls = "mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300";
const inputCls = "w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm transition placeholder:text-gray-400 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:border-[#2c2f3d] dark:bg-[#151720] dark:text-white";
const hintCls = "mt-1.5 text-xs text-gray-400";
const errCls = "mt-1 text-xs text-red-500";
const cardCls = "rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]";
const headerCls = "flex items-center gap-3 border-b border-gray-100 px-6 py-4 dark:border-[#2a2d38]";
const iconBadge = "flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-primary-500/10 text-primary-500";
const titleCls = "text-[15px] font-semibold text-gray-900 dark:text-white";
const subCls = "text-xs text-gray-400";

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

const sample = 1000;
const taxAmount = computed(() => (sample * Number(form.default_tax_rate ?? 0)) / 100);
const total = computed(() => sample + taxAmount.value);
const money = (v: number) => Number(v ?? 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + " " + (form.currency_symbol || "DH");
</script>
