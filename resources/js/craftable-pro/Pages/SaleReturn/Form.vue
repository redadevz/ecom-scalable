<template>
    <PageContent>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- ============ MAIN COLUMN ============ -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Return -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><ArrowUturnLeftIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Return</h3>
                            <p :class="subCls">Stock entry, description and internal notes.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <DatePicker
                            v-model="form.entry_stock_time"
                            name="entry_stock_time"
                            mode="dateTime"
                            label="Entry Stock Time"
                        />
                        <div class="sm:col-span-2">
                            <TextInput
                                v-model="form.description"
                                name="description"
                                label="Description"
                                type="text"
                            />
                        </div>
                        <div class="sm:col-span-2">
                            <TextInput
                                v-model="form.comments"
                                name="comments"
                                label="Comments"
                                type="text"
                            />
                        </div>
                    </div>
                </section>

                <!-- Refund -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><BanknotesIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Refund</h3>
                            <p :class="subCls">Refund amount and settlement time.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <TextInput
                            v-model="form.refund_amount"
                            name="refund_amount"
                            label="Refund Amount"
                            type="number"
                        />
                        <DatePicker
                            v-model="form.refund_time"
                            name="refund_time"
                            mode="dateTime"
                            label="Refund Time"
                        />
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
                            <ToggleRow v-model="form.is_refund_required" title="Refund required" desc="A refund must be issued for this return." />
                            <ToggleRow v-model="form.is_refunded" title="Refunded" desc="The refund has already been settled." />
                        </div>
                    </section>

                    <!-- Relations -->
                    <section :class="cardCls">
                        <header :class="headerCls">
                            <span :class="iconBadge"><LinkIcon class="h-5 w-5" /></span>
                            <h3 :class="titleCls">Relations</h3>
                        </header>
                        <div class="space-y-4 p-5">
                            <Multiselect
                                v-model="form.store_id"
                                name="store_id"
                                label="Store"
                                mode="single"
                                :options="$page.props.stores ?? []"
                                options-value-prop="id"
                                options-label="name"
                                :searchable="true"
                            />
                            <Multiselect
                                v-model="form.order_id"
                                name="order_id"
                                label="Order"
                                mode="single"
                                :options="$page.props.order_headers ?? []"
                                options-value-prop="id"
                                options-label="order_no"
                                :searchable="true"
                            />
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
    ArrowUturnLeftIcon,
    BanknotesIcon,
    AdjustmentsHorizontalIcon,
    LinkIcon,
} from "@heroicons/vue/24/outline";
import {
    TextInput,
    PageContent,
    Multiselect,
    DatePicker,
} from "craftable-pro/Components";
import { InertiaForm } from "craftable-pro/types";
import type { SaleReturnForm } from "./types";

interface Props {
    form: InertiaForm<SaleReturnForm>;
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
