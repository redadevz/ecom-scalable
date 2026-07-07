<template>
    <PageHeader :title="`Invoice ${invoice.invoice_no ?? '#' + invoice.id}`" :subtitle="`Issued ${fmtDate(invoice.created_at)}`">
        <div class="flex items-center gap-2">
            <Link :href="route('craftable-pro.invoices.index')"
                class="flex h-9 items-center gap-1.5 rounded-lg border border-gray-200 px-3 text-sm text-gray-600 hover:bg-gray-50 dark:border-[#2c2f3d] dark:text-gray-300 dark:hover:bg-white/5">
                <ArrowLeftIcon class="h-4 w-4" /> Back
            </Link>
            <Button :leftIcon="PencilSquareIcon" :as="Link" :href="route('craftable-pro.invoices.edit', invoice.id)" v-can="'craftable-pro.invoices.edit'">
                {{ $t("craftable-pro", "Edit") }}
            </Button>
        </div>
    </PageHeader>

    <PageContent>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <!-- Status -->
                <section :class="cardCls">
                    <div class="flex flex-wrap items-center gap-2 p-5">
                        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold" :class="invoice.is_paid ? greenPill : amberPill">
                            <span class="h-1.5 w-1.5 rounded-full" :class="invoice.is_paid ? 'bg-green-500' : 'bg-amber-500'"></span>
                            {{ invoice.is_paid ? 'Paid' : 'Unpaid' }}
                        </span>
                        <span v-if="invoice.order" class="text-sm text-gray-400">for order
                            <Link :href="route('craftable-pro.order-headers.show', invoice.order.id)" class="font-medium text-primary-600 hover:underline dark:text-primary-400">{{ invoice.order.order_no ?? '#' + invoice.order.id }}</Link>
                        </span>
                    </div>
                </section>

                <!-- Lines -->
                <section :class="cardCls">
                    <header :class="headerCls"><span :class="iconBadge"><ListBulletIcon class="h-5 w-5" /></span><h3 :class="titleCls">Lines</h3></header>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-100 text-left text-xs uppercase tracking-wide text-gray-400 dark:border-[#2a2d38]">
                                    <th class="px-6 py-3 font-medium">#</th>
                                    <th class="px-6 py-3 font-medium">Item</th>
                                    <th class="px-6 py-3 text-right font-medium">Qty</th>
                                    <th class="px-6 py-3 text-right font-medium">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="line in invoice.invoice_lines" :key="line.id" class="border-b border-gray-50 last:border-0 dark:border-[#23252f]">
                                    <td class="px-6 py-3 tabular-nums text-gray-400">{{ line.line_no }}</td>
                                    <td class="px-6 py-3">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ line.order_line?.item?.name ?? '—' }}</div>
                                        <div class="text-xs text-gray-400">{{ line.order_line?.item?.sku_code }}</div>
                                    </td>
                                    <td class="px-6 py-3 text-right tabular-nums">{{ line.order_line?.quantity ?? '—' }}</td>
                                    <td class="px-6 py-3 text-right font-medium tabular-nums text-gray-900 dark:text-white">{{ money(line.order_line?.price) }}</td>
                                </tr>
                                <tr v-if="!invoice.invoice_lines?.length"><td colspan="4" class="px-6 py-8 text-center text-gray-400">No lines</td></tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Payments -->
                <section :class="cardCls">
                    <header :class="headerCls"><span :class="iconBadge"><BanknotesIcon class="h-5 w-5" /></span><h3 :class="titleCls">Payments</h3></header>
                    <div class="p-6">
                        <div v-for="p in invoice.payments" :key="p.id" class="flex items-center justify-between border-b border-gray-50 py-2 text-sm last:border-0 dark:border-[#23252f]">
                            <div>
                                <span class="font-medium text-gray-900 dark:text-white">{{ p.payment_no }}</span>
                                <span class="ml-2 text-xs text-gray-400">{{ p.payment_method?.name }} · {{ fmtDate(p.payment_time) }}</span>
                            </div>
                            <span class="font-medium tabular-nums text-gray-900 dark:text-white">{{ money(p.amount) }}</span>
                        </div>
                        <p v-if="!invoice.payments?.length" class="text-sm text-gray-400">No payments recorded.</p>
                    </div>
                </section>
            </div>

            <!-- SIDE -->
            <div class="space-y-6">
                <div class="lg:sticky lg:top-24 space-y-6">
                    <section :class="cardCls">
                        <header :class="headerCls"><span :class="iconBadge"><BanknotesIcon class="h-5 w-5" /></span><h3 :class="titleCls">Summary</h3></header>
                        <dl class="space-y-2 p-5 text-sm">
                            <div class="flex justify-between text-gray-500"><dt>Order total</dt><dd class="tabular-nums">{{ money(invoice.order?.price) }}</dd></div>
                            <div class="flex justify-between text-gray-500"><dt>Paid</dt><dd class="tabular-nums">{{ money(paid) }}</dd></div>
                            <div class="mt-2 flex justify-between border-t border-gray-100 pt-3 text-base font-semibold dark:border-[#2a2d38]" :class="balance > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-green-600 dark:text-green-400'">
                                <dt>Balance</dt><dd class="tabular-nums">{{ money(balance) }}</dd>
                            </div>
                        </dl>
                    </section>

                    <section :class="cardCls">
                        <header :class="headerCls"><span :class="iconBadge"><UserIcon class="h-5 w-5" /></span><h3 :class="titleCls">Customer</h3></header>
                        <div class="p-5 text-sm">
                            <p class="font-medium text-gray-900 dark:text-white">{{ customerName }}</p>
                            <p class="text-xs text-gray-400">{{ invoice.order?.customer?.code ?? '—' }}</p>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </PageContent>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { ArrowLeftIcon, PencilSquareIcon, ListBulletIcon, BanknotesIcon, UserIcon } from "@heroicons/vue/24/outline";
import { PageHeader, PageContent, Button } from "craftable-pro/Components";
import dayjs from "dayjs";

interface Props { invoice: any }
const props = defineProps<Props>();

const cardCls = "rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]";
const headerCls = "flex items-center gap-3 border-b border-gray-100 px-6 py-4 dark:border-[#2a2d38]";
const iconBadge = "flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-primary-500/10 text-primary-500";
const titleCls = "text-[15px] font-semibold text-gray-900 dark:text-white";
const greenPill = "bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400";
const amberPill = "bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400";

const money = (v: any) => Number(v ?? 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + " DH";
const fmtDate = (d: any) => (d ? dayjs(d).format("DD MMM YYYY, HH:mm") : "—");

const paid = computed(() => (props.invoice.payments ?? []).reduce((s: number, p: any) => s + Number(p.amount ?? 0), 0));
const balance = computed(() => Math.max(0, Number(props.invoice.order?.price ?? 0) - paid.value));
const customerName = computed(() => {
    const c = props.invoice.order?.customer;
    if (!c) return "Walk-in";
    if (c.is_company && c.company_name) return c.company_name;
    return [c.first_name, c.last_name].filter(Boolean).join(" ") || c.company_name || "—";
});
</script>
