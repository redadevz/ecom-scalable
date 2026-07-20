<template>
    <PageContent>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- ============ MAIN COLUMN ============ -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Order -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><ShoppingBagIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Order</h3>
                            <p :class="subCls">Order number, current status and customer notes.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <AutoCodeInput :model-value="form.order_no" label="Order No" />
                        <TextInput v-model="form.latest_status" name="latest_status" label="Latest Status" type="text" />
                        <DatePicker v-model="form.latest_status_update" name="latest_status_update" mode="dateTime" label="Latest Status Update" />
                        <div class="sm:col-span-2">
                            <TextInput v-model="form.customer_notes" name="customer_notes" label="Customer Notes" type="text" />
                        </div>
                    </div>
                </section>

                <!-- Amounts -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><BanknotesIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Amounts</h3>
                            <p :class="subCls">Pricing, taxes, discounts and adjustments.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <TextInput v-model="form.price_before_tax" name="price_before_tax" label="Price Before Tax" type="number" />
                        <TextInput v-model="form.total_tax_value" name="total_tax_value" label="Total Tax Value" type="number" />
                        <TextInput v-model="form.price_after_tax" name="price_after_tax" label="Price After Tax" type="number" />
                        <TextInput v-model="form.price_before_discount" name="price_before_discount" label="Price Before Discount" type="number" />
                        <TextInput v-model="form.order_items_discount" name="order_items_discount" label="Order Items Discount" type="number" />
                        <TextInput v-model="form.order_discount" name="order_discount" label="Order Discount" type="number" />
                        <TextInput v-model="form.total_discount_value" name="total_discount_value" label="Total Discount Value" type="number" />
                        <TextInput v-model="form.price_after_discount" name="price_after_discount" label="Price After Discount" type="number" />
                        <TextInput v-model="form.price_adjustment" name="price_adjustment" label="Price Adjustment" type="number" />
                        <TextInput v-model="form.price" name="price" label="Price" type="number" />
                        <div class="sm:col-span-2">
                            <TextInput v-model="form.price_adjustment_reason" name="price_adjustment_reason" label="Price Adjustment Reason" type="text" />
                        </div>
                    </div>
                </section>

                <!-- Fulfilment -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><TruckIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Fulfilment</h3>
                            <p :class="subCls">Lifecycle timestamps and related notes.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <DatePicker v-model="form.submitted_time" name="submitted_time" mode="dateTime" label="Submitted Time" />
                        <DatePicker v-model="form.approved_time" name="approved_time" mode="dateTime" label="Approved Time" />
                        <DatePicker v-model="form.scheduled_time" name="scheduled_time" mode="dateTime" label="Scheduled Time" />
                        <DatePicker v-model="form.ready_time" name="ready_time" mode="dateTime" label="Ready Time" />
                        <DatePicker v-model="form.delivered_time" name="delivered_time" mode="dateTime" label="Delivered Time" />
                        <DatePicker v-model="form.payment_time" name="payment_time" mode="dateTime" label="Payment Time" />
                        <DatePicker v-model="form.completed_time" name="completed_time" mode="dateTime" label="Completed Time" />
                        <DatePicker v-model="form.canceled_time" name="canceled_time" mode="dateTime" label="Canceled Time" />
                        <DatePicker v-model="form.return_time" name="return_time" mode="dateTime" label="Return Time" />
                        <div class="sm:col-span-2">
                            <TextInput v-model="form.cancel_reason" name="cancel_reason" label="Cancel Reason" type="text" />
                        </div>
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
                            <ToggleRow v-model="form.is_submitted" title="Submitted" desc="Order has been submitted." />
                            <ToggleRow v-model="form.is_approved" title="Approved" desc="Order has been approved." />
                            <ToggleRow v-model="form.is_canceled" title="Canceled" desc="Order has been canceled." />
                            <ToggleRow v-model="form.is_scheduled" title="Scheduled" desc="Order is scheduled." />
                            <ToggleRow v-model="form.is_ready" title="Ready" desc="Order is ready." />
                            <ToggleRow v-model="form.is_delivered" title="Delivered" desc="Order has been delivered." />
                            <ToggleRow v-model="form.is_paid" title="Paid" desc="Payment has been received." />
                            <ToggleRow v-model="form.is_completed" title="Completed" desc="Order is completed." />
                            <ToggleRow v-model="form.return_required" title="Return required" desc="A return is required for this order." />
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
                            <Multiselect v-model="form.sale_channel_id" name="sale_channel_id" label="Sale Channel" mode="single"
                                :options="$page.props.sale_channels ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                            <Multiselect v-model="form.delivery_type_id" name="delivery_type_id" label="Delivery Type" mode="single"
                                :options="$page.props.delivery_types ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                            <Multiselect v-model="form.payment_method_id" name="payment_method_id" label="Payment Method" mode="single"
                                :options="$page.props.payment_methods ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                            <Multiselect v-model="form.payment_time_id" name="payment_time_id" label="Payment Time" mode="single"
                                :options="$page.props.payment_times ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                            <Multiselect v-model="form.customer_id" name="customer_id" label="Customer" mode="single"
                                :options="$page.props.customers ?? []" options-value-prop="id" options-label="code" :searchable="true" />
                            <Multiselect v-model="form.loyalty_card_id" name="loyalty_card_id" label="Loyalty Card" mode="single"
                                :options="$page.props.loyalty_cards ?? []" options-value-prop="id" options-label="code" :searchable="true" />
                            <Multiselect v-model="form.created_by" name="created_by" label="Created By" mode="single"
                                :options="$page.props.craftable_pro_users ?? []" options-value-prop="id" options-label="email" :searchable="true" />
                            <Multiselect v-model="form.approved_by" name="approved_by" label="Approved By" mode="single"
                                :options="$page.props.craftable_pro_users ?? []" options-value-prop="id" options-label="email" :searchable="true" />
                            <Multiselect v-model="form.managed_by" name="managed_by" label="Managed By" mode="single"
                                :options="$page.props.craftable_pro_users ?? []" options-value-prop="id" options-label="email" :searchable="true" />
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </PageContent>
</template>

<script setup lang="ts">
import { computed, h } from "vue";
import {
    ShoppingBagIcon,
    BanknotesIcon,
    TruckIcon,
    AdjustmentsHorizontalIcon,
    LinkIcon,
} from "@heroicons/vue/24/outline";
import {
    TextInput,
    PageContent,
    DatePicker,
    Multiselect,
} from "craftable-pro/Components";
import AutoCodeInput from "@/craftable-pro/Components/AutoCodeInput.vue";
import { InertiaForm } from "craftable-pro/types";
import type { OrderHeaderForm } from "./types";

interface Props {
    form: InertiaForm<OrderHeaderForm>;
    submit: void;
}
const props = defineProps<Props>();

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
