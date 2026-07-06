<template>
    <PageContent>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- ============ MAIN COLUMN ============ -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Payment -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><BanknotesIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Payment</h3>
                            <p :class="subCls">Reference, amounts and the moment it was paid.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <TextInput
                            v-model="form.payment_no"
                            name="payment_no"
                            label="Payment No"
                            type="text"
                        />
                        <TextInput
                            v-model="form.amount"
                            name="amount"
                            label="Amount"
                            type="number"
                        />
                        <TextInput
                            v-model="form.cash_paid"
                            name="cash_paid"
                            label="Cash Paid"
                            type="number"
                        />
                        <TextInput
                            v-model="form.cash_change"
                            name="cash_change"
                            label="Cash Change"
                            type="number"
                        />
                        <DatePicker
                            v-model="form.payment_time"
                            name="payment_time"
                            mode="dateTime"
                            label="Payment Time"
                        />
                    </div>
                </section>

                <!-- Notes -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><PencilSquareIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Notes</h3>
                            <p :class="subCls">Internal comments about this payment.</p>
                        </div>
                    </header>
                    <div class="p-6">
                        <TextInput
                            v-model="form.comments"
                            name="comments"
                            label="Comments"
                            type="text"
                        />
                    </div>
                </section>
            </div>

            <!-- ============ SIDE COLUMN ============ -->
            <div class="space-y-6">
                <div class="lg:sticky lg:top-24 space-y-6">
                    <!-- Relations -->
                    <section :class="cardCls">
                        <header :class="headerCls">
                            <span :class="iconBadge"><LinkIcon class="h-5 w-5" /></span>
                            <h3 :class="titleCls">Relations</h3>
                        </header>
                        <div class="space-y-4 p-5">
                            <Multiselect
                                v-model="form.invoice_id"
                                name="invoice_id"
                                label="Invoice"
                                mode="single"
                                :options="$page.props.invoices ?? []"
                                options-value-prop="id"
                                options-label="invoice_no"
                                :searchable="true"
                            />
                            <Multiselect
                                v-model="form.payment_method_id"
                                name="payment_method_id"
                                label="Payment Method"
                                mode="single"
                                :options="$page.props.payment_methods ?? []"
                                options-value-prop="id"
                                options-label="name"
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
import {
    BanknotesIcon,
    PencilSquareIcon,
    LinkIcon,
} from "@heroicons/vue/24/outline";
import {
    TextInput,
    PageContent,
    DatePicker,
    Multiselect,
} from "craftable-pro/Components";
import { InertiaForm } from "craftable-pro/types";
import type { PaymentForm } from "./types";

interface Props {
    form: InertiaForm<PaymentForm>;
    submit: void;
}
const props = defineProps<Props>();

const cardCls = "rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]";
const headerCls = "flex items-center gap-3 border-b border-gray-100 px-6 py-4 dark:border-[#2a2d38]";
const iconBadge = "flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-primary-500/10 text-primary-500";
const titleCls = "text-[15px] font-semibold text-gray-900 dark:text-white";
const subCls = "text-xs text-gray-400";
</script>
