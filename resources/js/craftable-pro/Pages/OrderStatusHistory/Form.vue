<template>
    <PageContent>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- ============ MAIN COLUMN ============ -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Timeline -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><ClockIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Timeline</h3>
                            <p :class="subCls">When this status started and ended.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <DatePicker v-model="form.start_time" name="start_time" mode="dateTime" label="Start Time" />
                        <DatePicker v-model="form.end_time" name="end_time" mode="dateTime" label="End Time" />
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
                            <Multiselect v-model="form.order_id" name="order_id" label="Order" mode="single"
                                :options="$page.props.order_headers ?? []" options-value-prop="id" options-label="order_no" :searchable="true" />
                            <Multiselect v-model="form.order_status_id" name="order_status_id" label="Order Status" mode="single"
                                :options="$page.props.order_statuses ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </PageContent>
</template>

<script setup lang="ts">
import {
    ClockIcon,
    LinkIcon,
} from "@heroicons/vue/24/outline";
import {
    PageContent,
    DatePicker,
    Multiselect,
} from "craftable-pro/Components";
import { InertiaForm } from "craftable-pro/types";
import type { OrderStatusHistoryForm } from "./types";

interface Props {
    form: InertiaForm<OrderStatusHistoryForm>;
    submit: void;
}
const props = defineProps<Props>();

const cardCls = "rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]";
const headerCls = "flex items-center gap-3 border-b border-gray-100 px-6 py-4 dark:border-[#2a2d38]";
const iconBadge = "flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-primary-500/10 text-primary-500";
const titleCls = "text-[15px] font-semibold text-gray-900 dark:text-white";
const subCls = "text-xs text-gray-400";
</script>
