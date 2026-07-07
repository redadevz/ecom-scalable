<template>
    <PageHeader :title="`Order ${order.order_no ?? '#' + order.id}`" :subtitle="`Placed ${fmtDate(order.created_at)}`">
        <div class="flex items-center gap-2">
            <Link :href="route('craftable-pro.order-headers.index')"
                class="flex h-9 items-center gap-1.5 rounded-lg border border-gray-200 px-3 text-sm text-gray-600 hover:bg-gray-50 dark:border-[#2c2f3d] dark:text-gray-300 dark:hover:bg-white/5">
                <ArrowLeftIcon class="h-4 w-4" /> Back
            </Link>
            <Button :leftIcon="PencilSquareIcon" :as="Link" :href="route('craftable-pro.order-headers.edit', order.id)" v-can="'craftable-pro.order-headers.edit'">
                {{ $t("craftable-pro", "Edit") }}
            </Button>
        </div>
    </PageHeader>

    <PageContent>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- MAIN -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Status banner -->
                <section :class="cardCls">
                    <div class="flex flex-wrap items-center gap-2 p-5">
                        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold"
                            :class="order.is_canceled ? redPill : (order.is_approved ? greenPill : grayPill)">
                            <span class="h-1.5 w-1.5 rounded-full" :class="order.is_canceled ? 'bg-red-500' : (order.is_approved ? 'bg-green-500' : 'bg-gray-400')"></span>
                            {{ order.is_canceled ? 'Canceled' : (order.is_approved ? 'Approved' : 'Draft') }}
                        </span>
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold"
                            :class="order.is_paid ? greenPill : amberPill">{{ order.is_paid ? 'Paid' : 'Unpaid' }}</span>
                        <span v-if="order.latest_status" class="inline-flex items-center rounded-full bg-primary-500/10 px-3 py-1 text-xs font-semibold text-primary-600 dark:text-primary-400">
                            {{ order.latest_status }}
                        </span>
                    </div>
                </section>

                <!-- Line items -->
                <section :class="cardCls">
                    <header :class="headerCls"><span :class="iconBadge"><ShoppingCartIcon class="h-5 w-5" /></span><h3 :class="titleCls">Items</h3></header>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-100 text-left text-xs uppercase tracking-wide text-gray-400 dark:border-[#2a2d38]">
                                    <th class="px-6 py-3 font-medium">Item</th>
                                    <th class="px-6 py-3 text-right font-medium">Qty</th>
                                    <th class="px-6 py-3 text-right font-medium">Unit</th>
                                    <th class="px-6 py-3 text-right font-medium">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="line in order.order_lines" :key="line.id"
                                    class="border-b border-gray-50 last:border-0 dark:border-[#23252f]" :class="line.is_canceled ? 'opacity-50 line-through' : ''">
                                    <td class="px-6 py-3">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ line.item?.name ?? '—' }}</div>
                                        <div class="text-xs text-gray-400">{{ line.item?.sku_code }}</div>
                                    </td>
                                    <td class="px-6 py-3 text-right tabular-nums">{{ line.quantity }}</td>
                                    <td class="px-6 py-3 text-right tabular-nums text-gray-500">{{ money(unit(line)) }}</td>
                                    <td class="px-6 py-3 text-right font-medium tabular-nums text-gray-900 dark:text-white">{{ money(line.price) }}</td>
                                </tr>
                                <tr v-if="!order.order_lines?.length"><td colspan="4" class="px-6 py-8 text-center text-gray-400">No line items</td></tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Status timeline -->
                <section :class="cardCls" v-if="order.order_status_histories?.length">
                    <header :class="headerCls"><span :class="iconBadge"><ClockIcon class="h-5 w-5" /></span><h3 :class="titleCls">Status history</h3></header>
                    <ol class="space-y-4 p-6">
                        <li v-for="h in order.order_status_histories" :key="h.id" class="flex gap-3">
                            <span class="mt-1 h-2 w-2 flex-shrink-0 rounded-full bg-primary-500"></span>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ h.order_status?.name ?? '—' }}</p>
                                <p class="text-xs text-gray-400">{{ fmtDate(h.start_time) }}<span v-if="h.end_time"> → {{ fmtDate(h.end_time) }}</span></p>
                            </div>
                        </li>
                    </ol>
                </section>
            </div>

            <!-- SIDE -->
            <div class="space-y-6">
                <div class="lg:sticky lg:top-24 space-y-6">
                    <!-- Totals -->
                    <section :class="cardCls">
                        <header :class="headerCls"><span :class="iconBadge"><BanknotesIcon class="h-5 w-5" /></span><h3 :class="titleCls">Summary</h3></header>
                        <dl class="space-y-2 p-5 text-sm">
                            <div class="flex justify-between text-gray-500"><dt>Before tax</dt><dd class="tabular-nums">{{ money(order.price_before_tax) }}</dd></div>
                            <div class="flex justify-between text-gray-500"><dt>Tax</dt><dd class="tabular-nums">{{ money(order.total_tax_value) }}</dd></div>
                            <div class="flex justify-between text-gray-500"><dt>Discount</dt><dd class="tabular-nums">− {{ money(order.total_discount_value) }}</dd></div>
                            <div class="mt-2 flex justify-between border-t border-gray-100 pt-3 text-base font-semibold text-gray-900 dark:border-[#2a2d38] dark:text-white">
                                <dt>Total</dt><dd class="tabular-nums text-primary-600 dark:text-primary-400">{{ money(order.price) }}</dd>
                            </div>
                        </dl>
                    </section>

                    <!-- Customer -->
                    <section :class="cardCls">
                        <header :class="headerCls"><span :class="iconBadge"><UserIcon class="h-5 w-5" /></span><h3 :class="titleCls">Customer</h3></header>
                        <div class="p-5 text-sm">
                            <p class="font-medium text-gray-900 dark:text-white">{{ customerName }}</p>
                            <p class="text-xs text-gray-400">{{ order.customer?.code ?? '—' }}</p>
                            <p class="mt-3 border-t border-gray-100 pt-3 text-gray-500 dark:border-[#2a2d38]">Store: <span class="text-gray-700 dark:text-gray-300">{{ order.store?.name ?? '—' }}</span></p>
                        </div>
                    </section>

                    <!-- Invoices -->
                    <section :class="cardCls">
                        <header :class="headerCls"><span :class="iconBadge"><DocumentTextIcon class="h-5 w-5" /></span><h3 :class="titleCls">Invoices</h3></header>
                        <div class="p-5">
                            <div v-for="inv in order.invoices" :key="inv.id" class="flex items-center justify-between py-1.5 text-sm">
                                <Link :href="route('craftable-pro.invoices.show', inv.id)" class="font-medium text-primary-600 hover:underline dark:text-primary-400">{{ inv.invoice_no }}</Link>
                                <span class="rounded-full px-2 py-0.5 text-xs font-medium" :class="inv.is_paid ? greenPill : amberPill">{{ inv.is_paid ? 'Paid' : 'Unpaid' }}</span>
                            </div>
                            <p v-if="!order.invoices?.length" class="text-sm text-gray-400">Not invoiced yet.</p>
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
import { ArrowLeftIcon, PencilSquareIcon, ShoppingCartIcon, ClockIcon, BanknotesIcon, UserIcon, DocumentTextIcon } from "@heroicons/vue/24/outline";
import { PageHeader, PageContent, Button } from "craftable-pro/Components";
import dayjs from "dayjs";

interface Props { order: any }
const props = defineProps<Props>();

const cardCls = "rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]";
const headerCls = "flex items-center gap-3 border-b border-gray-100 px-6 py-4 dark:border-[#2a2d38]";
const iconBadge = "flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-primary-500/10 text-primary-500";
const titleCls = "text-[15px] font-semibold text-gray-900 dark:text-white";
const greenPill = "bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400";
const amberPill = "bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400";
const redPill = "bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400";
const grayPill = "bg-gray-100 text-gray-500 dark:bg-white/5 dark:text-gray-400";

const money = (v: any) => Number(v ?? 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + " DH";
const fmtDate = (d: any) => (d ? dayjs(d).format("DD MMM YYYY, HH:mm") : "—");
const unit = (line: any) => (line.quantity ? Number(line.price_after_tax ?? 0) / Number(line.quantity) : 0);

const customerName = computed(() => {
    const c = props.order.customer;
    if (!c) return "Walk-in";
    if (c.is_company && c.company_name) return c.company_name;
    return [c.first_name, c.last_name].filter(Boolean).join(" ") || c.company_name || "—";
});
</script>
