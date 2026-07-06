<template>
    <PageContent>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- ============ MAIN COLUMN ============ -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Line -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><DocumentTextIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Line</h3>
                            <p :class="subCls">Position number of this line on the invoice.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <TextInput v-model="form.line_no" name="line_no" label="Line No" type="text" />
                    </div>
                </section>

                <!-- Notes -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><PencilSquareIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Notes</h3>
                            <p :class="subCls">Free-text comments for this invoice line.</p>
                        </div>
                    </header>
                    <div class="p-6">
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
                            <DocumentTextIcon class="absolute -right-4 -top-4 h-32 w-32 text-white/15" />
                            <div class="absolute right-3 top-3 rounded-full bg-white/20 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-white backdrop-blur">
                                {{ $t("craftable-pro", "Invoice Line") }}
                            </div>
                        </div>
                        <div class="px-5 pb-5">
                            <div class="-mt-12 mb-3 flex h-24 w-24 items-center justify-center rounded-2xl border-4 border-white bg-gradient-to-br from-primary-500 to-orange-600 text-2xl font-bold uppercase text-white shadow-lg ring-1 ring-black/5 dark:border-[#1e2029]">
                                {{ initials }}
                            </div>
                            <h3 class="truncate text-lg font-semibold text-gray-900 dark:text-white">Line {{ form.line_no || '—' }}</h3>
                            <p class="text-sm text-gray-400 truncate">{{ form.comments || 'No comments yet.' }}</p>
                        </div>
                    </section>

                    <!-- Relations -->
                    <section :class="cardCls">
                        <header :class="headerCls">
                            <span :class="iconBadge"><LinkIcon class="h-5 w-5" /></span>
                            <h3 :class="titleCls">Relations</h3>
                        </header>
                        <div class="space-y-4 p-5">
                            <Multiselect v-model="form.invoice_id" name="invoice_id" label="Invoice" mode="single"
                                :options="$page.props.invoices ?? []" options-value-prop="id" options-label="invoice_no" :searchable="true" />
                            <Multiselect v-model="form.order_line_id" name="order_line_id" label="Order Line" mode="single"
                                :options="$page.props.order_lines ?? []" options-value-prop="id" options-label="line_no" :searchable="true" />
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
    DocumentTextIcon,
    PencilSquareIcon,
    LinkIcon,
} from "@heroicons/vue/24/outline";
import {
    TextInput,
    PageContent,
    Multiselect,
} from "craftable-pro/Components";
import { InertiaForm } from "craftable-pro/types";
import type { InvoiceLineForm } from "./types";

interface Props {
    form: InertiaForm<InvoiceLineForm>;
    submit: void;
}
const props = defineProps<Props>();

const cardCls = "rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]";
const headerCls = "flex items-center gap-3 border-b border-gray-100 px-6 py-4 dark:border-[#2a2d38]";
const iconBadge = "flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-primary-500/10 text-primary-500";
const titleCls = "text-[15px] font-semibold text-gray-900 dark:text-white";
const subCls = "text-xs text-gray-400";

const initials = computed(() => {
    const base = (props.form.line_no ?? "").toString().trim();
    return base ? base.slice(0, 2).toUpperCase() : "IL";
});
</script>
