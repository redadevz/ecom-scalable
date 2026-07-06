<template>
    <PageContent>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- ============ MAIN COLUMN ============ -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Line -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><QueueListIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Line</h3>
                            <p :class="subCls">Line reference, description and quantity.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <TextInput v-model="form.line_no" name="line_no" label="Line No" type="text" />
                        <TextInput v-model="form.quantity" name="quantity" label="Quantity" type="number" />
                        <div class="sm:col-span-2">
                            <TextInput v-model="form.description" name="description" label="Description" type="text" />
                        </div>
                        <div class="sm:col-span-2">
                            <TextInput v-model="form.customer_notes" name="customer_notes" label="Customer Notes" type="text" />
                        </div>
                    </div>
                </section>

                <!-- Pricing -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><CurrencyDollarIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Pricing</h3>
                            <p :class="subCls">Cost, markup and tax computation.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <TextInput v-model="form.current_item_cost" name="current_item_cost" label="Current Item Cost" type="number" />
                        <TextInput v-model="form.markup_percentage" name="markup_percentage" label="Markup Percentage" type="number" />
                        <TextInput v-model="form.price_before_tax" name="price_before_tax" label="Price Before Tax" type="number" />
                        <TextInput v-model="form.tax_value" name="tax_value" label="Tax Value" type="number" />
                        <TextInput v-model="form.price_after_tax" name="price_after_tax" label="Price After Tax" type="number" />
                        <TextInput v-model="form.price" name="price" label="Price" type="number" />
                    </div>
                </section>

                <!-- Discounts &amp; Adjustments -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><ReceiptPercentIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Discounts &amp; Adjustments</h3>
                            <p :class="subCls">Discount amounts and manual price adjustments.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <TextInput v-model="form.price_before_discount" name="price_before_discount" label="Price Before Discount" type="number" />
                        <TextInput v-model="form.discount_value" name="discount_value" label="Discount Value" type="number" />
                        <TextInput v-model="form.price_after_discount" name="price_after_discount" label="Price After Discount" type="number" />
                        <TextInput v-model="form.price_adjustment" name="price_adjustment" label="Price Adjustment" type="number" />
                        <div class="sm:col-span-2">
                            <TextInput v-model="form.price_adjustment_reason" name="price_adjustment_reason" label="Price Adjustment Reason" type="text" />
                        </div>
                    </div>
                </section>

                <!-- Fulfillment &amp; Returns -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><ArrowUturnLeftIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Fulfillment &amp; Returns</h3>
                            <p :class="subCls">Cancellation, returns and customer feedback.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <DatePicker v-model="form.canceled_time" name="canceled_time" mode="dateTime" label="Canceled Time" />
                        <div class="sm:col-span-2">
                            <TextInput v-model="form.cancel_reason" name="cancel_reason" label="Cancel Reason" type="text" />
                        </div>
                        <TextInput v-model="form.return_quantity" name="return_quantity" label="Return Quantity" type="number" />
                        <DatePicker v-model="form.return_time" name="return_time" mode="dateTime" label="Return Time" />
                        <TextInput v-model="form.customer_review" name="customer_review" label="Customer Review" type="text" />
                        <div class="sm:col-span-2">
                            <TextInput v-model="form.comments" name="comments" label="Comments" type="text" />
                        </div>
                    </div>
                </section>
            </div>

            <!-- ============ SIDE COLUMN ============ -->
            <div class="space-y-6">
                <div class="lg:sticky lg:top-24 space-y-6">
                    <!-- Flags -->
                    <section :class="cardCls">
                        <header :class="headerCls">
                            <span :class="iconBadge"><AdjustmentsHorizontalIcon class="h-5 w-5" /></span>
                            <h3 :class="titleCls">Flags</h3>
                        </header>
                        <div class="divide-y divide-gray-100 dark:divide-[#2a2d38]">
                            <ToggleRow v-model="form.is_canceled" title="Canceled" desc="Mark this order line as canceled." />
                            <ToggleRow v-model="form.return_required" title="Return required" desc="A return is required for this line." />
                            <ToggleRow v-model="form.customer_like" title="Customer like" desc="Customer liked this item." />
                        </div>
                    </section>

                    <!-- Relations -->
                    <section :class="cardCls">
                        <header :class="headerCls">
                            <span :class="iconBadge"><LinkIcon class="h-5 w-5" /></span>
                            <h3 :class="titleCls">Relations</h3>
                        </header>
                        <div class="space-y-4 p-5">
                            <Multiselect v-model="form.store_id" name="store_id" label="Store" mode="single"
                                :options="$page.props.stores ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                            <Multiselect v-model="form.order_id" name="order_id" label="Order" mode="single"
                                :options="$page.props.order_headers ?? []" options-value-prop="id" options-label="order_no" :searchable="true" />
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
import { h } from "vue";
import {
    QueueListIcon,
    CurrencyDollarIcon,
    ReceiptPercentIcon,
    ArrowUturnLeftIcon,
    AdjustmentsHorizontalIcon,
    LinkIcon,
} from "@heroicons/vue/24/outline";
import {
    TextInput,
    PageContent,
    DatePicker,
    Multiselect,
} from "craftable-pro/Components";
import { InertiaForm } from "craftable-pro/types";
import type { OrderLineForm } from "./types";

interface Props {
    form: InertiaForm<OrderLineForm>;
    submit: void;
}
defineProps<Props>();

const cardCls = "rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]";
const headerCls = "flex items-center gap-3 border-b border-gray-100 px-6 py-4 dark:border-[#2a2d38]";
const iconBadge = "flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-primary-500/10 text-primary-500";
const titleCls = "text-[15px] font-semibold text-gray-900 dark:text-white";
const subCls = "text-xs text-gray-400";

// Inline toggle row component
const ToggleRow = (p: any, { emit }: any) =>
    h("div", { class: "flex items-center justify-between gap-4 p-5" }, [
        h("div", {}, [
            h("p", { class: "text-sm font-medium text-gray-900 dark:text-white" }, p.title),
            h("p", { class: "text-xs text-gray-400" }, p.desc),
        ]),
        h("button", {
            type: "button",
            onClick: () => emit("update:modelValue", !p.modelValue),
            class: [
                "relative inline-flex h-6 w-11 flex-shrink-0 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-[#1e2029]",
                p.modelValue ? "bg-primary-500" : "bg-gray-300 dark:bg-[#3a3d4d]",
            ],
        }, [
            h("span", {
                class: [
                    "inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform",
                    p.modelValue ? "translate-x-6" : "translate-x-1",
                ],
            }),
        ]),
    ]);
(ToggleRow as any).props = ["modelValue", "title", "desc"];
(ToggleRow as any).emits = ["update:modelValue"];
</script>
