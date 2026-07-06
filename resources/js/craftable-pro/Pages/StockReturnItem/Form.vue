<template>
    <PageContent>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- ============ MAIN COLUMN ============ -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Line -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><CalculatorIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Line</h3>
                            <p :class="subCls">Quantity returned and the supplier amounts for this line.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <TextInput v-model="form.quantity" name="quantity" label="Quantity" type="number" />
                        <TextInput v-model="form.supplier_price_before_tax" name="supplier_price_before_tax" label="Supplier Price Before Tax" type="number" />
                        <TextInput v-model="form.supplier_tax_value" name="supplier_tax_value" label="Supplier Tax Value" type="number" />
                        <TextInput v-model="form.supplier_price_after_tax" name="supplier_price_after_tax" label="Supplier Price After Tax" type="number" />
                        <TextInput v-model="form.supplier_discount_value" name="supplier_discount_value" label="Supplier Discount Value" type="number" />
                        <TextInput v-model="form.return_amount" name="return_amount" label="Return Amount" type="number" />
                    </div>
                </section>

                <!-- Notes -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><PencilSquareIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Notes</h3>
                            <p :class="subCls">Description and internal comments for this line.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6">
                        <TextInput v-model="form.description" name="description" label="Description" type="text" />
                        <TextInput v-model="form.comments" name="comments" label="Comments" type="text" />
                    </div>
                </section>
            </div>

            <!-- ============ SIDE COLUMN ============ -->
            <div class="space-y-6">
                <div class="lg:sticky lg:top-24 space-y-6">
                    <!-- Live preview -->
                    <section class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]">
                        <div class="relative h-28 bg-gradient-to-br from-primary-500 via-orange-500 to-amber-400">
                            <ArrowUturnLeftIcon class="absolute -right-4 -top-4 h-32 w-32 text-white/15" />
                            <div class="absolute right-3 top-3 rounded-full bg-white/20 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-white backdrop-blur">
                                Return line
                            </div>
                        </div>
                        <div class="px-5 pb-5">
                            <div class="-mt-12 mb-3 flex h-24 w-24 items-center justify-center rounded-2xl border-4 border-white bg-gradient-to-br from-primary-500 to-orange-600 text-2xl font-bold text-white shadow-lg ring-1 ring-black/5 dark:border-[#1e2029]">
                                {{ Number(form.quantity ?? 0) }}
                            </div>
                            <h3 class="truncate text-lg font-semibold text-gray-900 dark:text-white">Return Amount</h3>
                            <p class="text-sm text-gray-400">{{ money(form.return_amount) }}</p>

                            <dl class="mt-4 space-y-2 border-t border-gray-100 pt-4 text-sm dark:border-[#2a2d38]">
                                <div class="flex items-center justify-between text-gray-600 dark:text-gray-300">
                                    <span>Price after tax</span><span class="tabular-nums">{{ money(form.supplier_price_after_tax) }}</span>
                                </div>
                                <div class="flex items-center justify-between text-gray-600 dark:text-gray-300">
                                    <span>Tax value</span><span class="tabular-nums">{{ money(form.supplier_tax_value) }}</span>
                                </div>
                                <div class="flex items-center justify-between text-gray-600 dark:text-gray-300">
                                    <span>Discount</span><span class="tabular-nums">{{ money(form.supplier_discount_value) }}</span>
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
                            <Multiselect v-model="form.stock_return_id" name="stock_return_id" label="Stock Return" mode="single"
                                :options="$page.props.stock_returns ?? []" options-value-prop="id" options-label="description" :searchable="true" />
                            <Multiselect v-model="form.item_id" name="item_id" label="Item" mode="single"
                                :options="$page.props.items ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </PageContent>
</template>

<script setup lang="ts">
import {
    CalculatorIcon,
    PencilSquareIcon,
    LinkIcon,
    ArrowUturnLeftIcon,
} from "@heroicons/vue/24/outline";
import {
    TextInput,
    PageContent,
    Multiselect,
} from "craftable-pro/Components";
import { InertiaForm } from "craftable-pro/types";
import type { StockReturnItemForm } from "./types";

interface Props {
    form: InertiaForm<StockReturnItemForm>;
    submit: void;
}
const props = defineProps<Props>();

const cardCls = "rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]";
const headerCls = "flex items-center gap-3 border-b border-gray-100 px-6 py-4 dark:border-[#2a2d38]";
const iconBadge = "flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-primary-500/10 text-primary-500";
const titleCls = "text-[15px] font-semibold text-gray-900 dark:text-white";
const subCls = "text-xs text-gray-400";

const money = (v: any) =>
    (isNaN(Number(v)) ? 0 : Number(v)).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + " DH";
</script>
