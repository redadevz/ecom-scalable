<template>
    <Wizard :steps="steps" :processing="form.processing" :errors="form.errors" :field-step="fieldStep"
        submit-label="Save order" max-width="4xl" @submit="submit">

        <!-- STEP 1 · ORDER -->
        <template #order>
            <div class="space-y-6 p-6 sm:p-8">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Order</h2>

                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-600 dark:text-gray-300">Order No</label>
                        <input :value="form.order_no" readonly placeholder="Generated on save" :class="codeCls" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-600 dark:text-gray-300">Latest status</label>
                        <Multiselect v-model="form.latest_status" name="latest_status" mode="single"
                            :options="statusOptions" options-value-prop="value" options-label="label" :searchable="true" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-600 dark:text-gray-300">Latest status update</label>
                        <DatePicker v-model="form.latest_status_update" name="latest_status_update" mode="dateTime" />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-gray-600 dark:text-gray-300">Customer notes</label>
                        <input v-model="form.customer_notes" type="text" :class="inputCls" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 border-t border-gray-100 pt-6 dark:border-[#2a2d38] sm:grid-cols-2">
                    <div v-for="r in orderRelations" :key="r.key">
                        <label class="mb-1 block text-sm font-medium text-gray-600 dark:text-gray-300">{{ r.label }}</label>
                        <Multiselect v-model="(form as any)[r.key]" :name="r.key" mode="single"
                            :options="($page.props as any)[r.opts] ?? []" options-value-prop="id" :options-label="r.ol" :searchable="true" />
                    </div>
                </div>
            </div>
        </template>

        <!-- STEP 2 · AMOUNTS -->
        <template #amounts>
            <div class="space-y-5 p-6 sm:p-8">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Amounts</h2>
                <p class="text-sm text-gray-400">These are normally computed when an order is confirmed.</p>
                <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-3">
                    <div v-for="f in moneyFields" :key="f.key">
                        <label class="mb-1 block text-sm font-medium text-gray-600 dark:text-gray-300">{{ f.label }}</label>
                        <input v-model="(form as any)[f.key]" type="number" step="0.01" :class="inputCls" placeholder="0.00" />
                    </div>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-600 dark:text-gray-300">Price adjustment reason</label>
                    <input v-model="form.price_adjustment_reason" type="text" :class="inputCls" />
                </div>
            </div>
        </template>

        <!-- STEP 3 · STATUS -->
        <template #status>
            <div class="space-y-6 p-6 sm:p-8">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Status &amp; fulfilment</h2>

                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                    <div v-for="fl in flags" :key="fl.key" class="rounded-xl border border-gray-200 dark:border-[#2c2f3d]">
                        <Toggle :model-value="(form as any)[fl.key]" @update:model-value="(v: boolean) => (form as any)[fl.key] = v" :title="fl.title" :desc="fl.desc" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-x-6 gap-y-4 border-t border-gray-100 pt-6 dark:border-[#2a2d38] sm:grid-cols-3">
                    <div v-for="t in timeFields" :key="t.key">
                        <label class="mb-1 block text-sm font-medium text-gray-600 dark:text-gray-300">{{ t.label }}</label>
                        <DatePicker v-model="(form as any)[t.key]" :name="t.key" mode="dateTime" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 border-t border-gray-100 pt-6 dark:border-[#2a2d38] sm:grid-cols-3">
                    <div v-for="r in staffRelations" :key="r.key">
                        <label class="mb-1 block text-sm font-medium text-gray-600 dark:text-gray-300">{{ r.label }}</label>
                        <Multiselect v-model="(form as any)[r.key]" :name="r.key" mode="single"
                            :options="$page.props.craftable_pro_users ?? []" options-value-prop="id" options-label="email" :searchable="true" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-x-6 gap-y-4 border-t border-gray-100 pt-6 dark:border-[#2a2d38] sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-600 dark:text-gray-300">Cancel reason</label>
                        <input v-model="form.cancel_reason" type="text" :class="inputCls" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-600 dark:text-gray-300">Comments</label>
                        <input v-model="form.comments" type="text" :class="inputCls" />
                    </div>
                </div>
            </div>
        </template>
    </Wizard>
</template>

<script setup lang="ts">
import { computed, h } from "vue";
import { usePage } from "@inertiajs/vue3";
import { DatePicker, Multiselect } from "craftable-pro/Components";
import Wizard from "@/craftable-pro/Components/Wizard.vue";
import { InertiaForm } from "craftable-pro/types";
import type { OrderHeaderForm } from "./types";

interface Props {
    form: InertiaForm<OrderHeaderForm>;
    submit: () => void;
}
defineProps<Props>();

// `latest_status` stores the status name (a string), so options use the name as both value and label
const page = usePage();
const statusOptions = computed(() => (((page.props as any).order_statuses as string[]) ?? []).map((n) => ({ value: n, label: n })));

const steps = [
    { key: "order", label: "Order" },
    { key: "amounts", label: "Amounts" },
    { key: "status", label: "Status" },
];

const orderRelations = [
    { key: "customer_id", label: "Customer", opts: "customers", ol: "code" },
    { key: "store_id", label: "Store", opts: "stores", ol: "name" },
    { key: "sale_channel_id", label: "Sale channel", opts: "sale_channels", ol: "name" },
    { key: "delivery_type_id", label: "Delivery type", opts: "delivery_types", ol: "name" },
    { key: "payment_method_id", label: "Payment method", opts: "payment_methods", ol: "name" },
    { key: "payment_time_id", label: "Payment time", opts: "payment_times", ol: "name" },
    { key: "loyalty_card_id", label: "Loyalty card", opts: "loyalty_cards", ol: "code" },
];
const staffRelations = [
    { key: "created_by", label: "Created by" },
    { key: "approved_by", label: "Approved by" },
    { key: "managed_by", label: "Managed by" },
];

const moneyFields = [
    { key: "price_before_tax", label: "Price before tax" },
    { key: "total_tax_value", label: "Total tax value" },
    { key: "price_after_tax", label: "Price after tax" },
    { key: "price_before_discount", label: "Price before discount" },
    { key: "order_items_discount", label: "Order items discount" },
    { key: "order_discount", label: "Order discount" },
    { key: "total_discount_value", label: "Total discount value" },
    { key: "price_after_discount", label: "Price after discount" },
    { key: "price_adjustment", label: "Price adjustment" },
    { key: "price", label: "Price" },
];
const timeFields = [
    { key: "submitted_time", label: "Submitted" },
    { key: "approved_time", label: "Approved" },
    { key: "scheduled_time", label: "Scheduled" },
    { key: "ready_time", label: "Ready" },
    { key: "delivered_time", label: "Delivered" },
    { key: "payment_time", label: "Payment" },
    { key: "completed_time", label: "Completed" },
    { key: "canceled_time", label: "Canceled" },
    { key: "return_time", label: "Return" },
];
const flags = [
    { key: "is_submitted", title: "Submitted", desc: "Order has been submitted." },
    { key: "is_approved", title: "Approved", desc: "Order has been approved." },
    { key: "is_canceled", title: "Canceled", desc: "Order has been canceled." },
    { key: "is_scheduled", title: "Scheduled", desc: "Order is scheduled." },
    { key: "is_ready", title: "Ready", desc: "Order is ready." },
    { key: "is_delivered", title: "Delivered", desc: "Order has been delivered." },
    { key: "is_paid", title: "Paid", desc: "Payment has been received." },
    { key: "is_completed", title: "Completed", desc: "Order is completed." },
    { key: "return_required", title: "Return required", desc: "A return is required for this order." },
];

// build the error-jump map from the field groups
const fieldStep: Record<string, number> = {
    order_no: 0, latest_status: 0, latest_status_update: 0, customer_notes: 0,
    ...Object.fromEntries(orderRelations.map((r) => [r.key, 0])),
    ...Object.fromEntries(moneyFields.map((f) => [f.key, 1])),
    price_adjustment_reason: 1,
    ...Object.fromEntries(flags.map((f) => [f.key, 2])),
    ...Object.fromEntries(timeFields.map((t) => [t.key, 2])),
    ...Object.fromEntries(staffRelations.map((r) => [r.key, 2])),
    cancel_reason: 2, comments: 2,
};

const inputCls = "w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 dark:border-[#2c2f3d] dark:bg-[#171923] dark:text-white";
const codeCls = "w-full cursor-not-allowed select-all rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium text-gray-700 focus:outline-none dark:border-[#2c2f3d] dark:bg-[#1a1c27] dark:text-gray-200";

const Toggle = (p: any, { emit }: any) =>
    h("div", { class: "flex items-center justify-between gap-4 p-4" }, [
        h("div", {}, [
            h("p", { class: "text-sm font-medium text-gray-900 dark:text-white" }, p.title),
            h("p", { class: "text-xs text-gray-400" }, p.desc),
        ]),
        h("button", {
            type: "button",
            onClick: () => emit("update:modelValue", !p.modelValue),
            class: ["relative inline-flex h-6 w-11 flex-shrink-0 items-center rounded-full transition-colors focus:outline-none", p.modelValue ? "bg-primary-500" : "bg-gray-300 dark:bg-[#3a3d4d]"],
        }, [h("span", { class: ["inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform", p.modelValue ? "translate-x-6" : "translate-x-1"] })]),
    ]);
(Toggle as any).props = ["modelValue", "title", "desc"];
(Toggle as any).emits = ["update:modelValue"];
</script>
