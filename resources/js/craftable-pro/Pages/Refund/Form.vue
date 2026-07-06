<template>
    <PageContent>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- ============ MAIN COLUMN ============ -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Refund -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><BanknotesIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Refund</h3>
                            <p :class="subCls">Reference, amounts and the time the refund was issued.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <TextInput v-model="form.refund_no" name="refund_no" label="Refund No" type="text" />
                        <DatePicker v-model="form.refund_time" name="refund_time" mode="dateTime" label="Refund Time" />
                        <TextInput v-model="form.amount" name="amount" label="Amount" type="number" />
                        <TextInput v-model="form.cash_paid" name="cash_paid" label="Cash Paid" type="number" />
                        <TextInput v-model="form.cash_change" name="cash_change" label="Cash Change" type="number" />
                        <div class="sm:col-span-2">
                            <TextInput v-model="form.comments" name="comments" label="Comments" type="text" />
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
                                {{ form.refund_time ? 'Refunded' : 'Pending' }}
                            </div>
                        </div>
                        <div class="px-5 pb-5">
                            <div class="-mt-12 mb-3 flex h-24 w-24 items-center justify-center rounded-2xl border-4 border-white bg-gradient-to-br from-primary-500 to-orange-600 text-2xl font-bold uppercase text-white shadow-lg ring-1 ring-black/5 dark:border-[#1e2029]">
                                {{ initials }}
                            </div>
                            <div class="flex items-center gap-2">
                                <h3 class="truncate text-lg font-semibold text-gray-900 dark:text-white">{{ form.refund_no || 'New Refund' }}</h3>
                            </div>
                            <p class="text-2xl font-bold text-primary-600 dark:text-primary-400">{{ money(form.amount) }}</p>

                            <dl class="mt-4 space-y-2 border-t border-gray-100 pt-4 text-sm dark:border-[#2a2d38]">
                                <div class="flex items-center justify-between gap-2 text-gray-600 dark:text-gray-300">
                                    <span class="text-gray-400">Cash paid</span><span class="font-medium">{{ money(form.cash_paid) }}</span>
                                </div>
                                <div class="flex items-center justify-between gap-2 text-gray-600 dark:text-gray-300">
                                    <span class="text-gray-400">Cash change</span><span class="font-medium">{{ money(form.cash_change) }}</span>
                                </div>
                                <div v-if="form.refund_time" class="flex items-center justify-between gap-2 text-gray-600 dark:text-gray-300">
                                    <span class="text-gray-400">Refund time</span><span class="font-medium">{{ form.refund_time }}</span>
                                </div>
                            </dl>
                        </div>
                    </section>

                    <!-- Relations -->
                    <section :class="cardCls">
                        <header :class="headerCls">
                            <span :class="iconBadge"><LinkIcon class="h-5 w-5" /></span>
                            <h3 :class="titleCls">Relations</h3>
                        </header>
                        <div class="space-y-4 p-5">
                            <Multiselect v-model="form.sale_return_id" name="sale_return_id" label="Sale Return" mode="single"
                                :options="$page.props.sale_returns ?? []" options-value-prop="id" options-label="description" :searchable="true" />
                            <Multiselect v-model="form.payment_method_id" name="payment_method_id" label="Payment Method" mode="single"
                                :options="$page.props.payment_methods ?? []" options-value-prop="id" options-label="name" :searchable="true" />
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
    BanknotesIcon,
    LinkIcon,
} from "@heroicons/vue/24/outline";
import {
    TextInput,
    PageContent,
    DatePicker,
    Multiselect,
} from "craftable-pro/Components";
import { InertiaForm } from "craftable-pro/types";
import type { RefundForm } from "./types";

interface Props {
    form: InertiaForm<RefundForm>;
    submit: void;
}
const props = defineProps<Props>();

const cardCls = "rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]";
const headerCls = "flex items-center gap-3 border-b border-gray-100 px-6 py-4 dark:border-[#2a2d38]";
const iconBadge = "flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-primary-500/10 text-primary-500";
const titleCls = "text-[15px] font-semibold text-gray-900 dark:text-white";
const subCls = "text-xs text-gray-400";

const money = (v: any) =>
    Number(v ?? 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + " DH";

const initials = computed(() => {
    const base = (props.form.refund_no || "").toString().trim();
    if (!base) return "R";
    return base.slice(0, 2).toUpperCase();
});
</script>
