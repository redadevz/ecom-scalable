<template>
    <PageContent>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- ============ MAIN COLUMN ============ -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Shipment details -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><TruckIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Shipment details</h3>
                            <p :class="subCls">Delivery address and dispatch information.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <TextInput v-model="form.shipment_address" name="shipment_address" label="Shipment Address" type="text" />
                        </div>
                        <TextInput v-model="form.gps_location" name="gps_location" label="Gps Location" type="text" />
                        <TextInput v-model="form.postal_code" name="postal_code" label="Postal Code" type="text" />
                        <div class="sm:col-span-2">
                            <TextInput v-model="form.shipment_notes" name="shipment_notes" label="Shipment Notes" type="text" />
                        </div>
                        <div class="sm:col-span-2">
                            <TextInput v-model="form.comments" name="comments" label="Comments" type="text" />
                        </div>
                    </div>
                </section>

                <!-- Timeline -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><ClockIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Timeline</h3>
                            <p :class="subCls">When the shipment was picked up and shipped.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <DatePicker v-model="form.picked_up_time" name="picked_up_time" mode="dateTime" label="Picked Up Time" />
                        <DatePicker v-model="form.shipped_time" name="shipped_time" mode="dateTime" label="Shipped Time" />
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
                            <Multiselect v-model="form.store_id" name="store_id" label="Store" mode="single"
                                :options="$page.props.stores ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                            <Multiselect v-model="form.order_id" name="order_id" label="Order" mode="single"
                                :options="$page.props.order_headers ?? []" options-value-prop="id" options-label="order_no" :searchable="true" />
                            <Multiselect v-model="form.shipment_city_id" name="shipment_city_id" label="Shipment City" mode="single"
                                :options="$page.props.cities ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                            <Multiselect v-model="form.picked_up_by" name="picked_up_by" label="Picked Up By" mode="single"
                                :options="$page.props.craftable_pro_users ?? []" options-value-prop="id" options-label="email" :searchable="true" />
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </PageContent>
</template>

<script setup lang="ts">
import {
    TruckIcon,
    ClockIcon,
    LinkIcon,
} from "@heroicons/vue/24/outline";
import {
    TextInput,
    PageContent,
    DatePicker,
    Multiselect,
} from "craftable-pro/Components";
import { InertiaForm } from "craftable-pro/types";
import type { ShipmentForm } from "./types";

interface Props {
    form: InertiaForm<ShipmentForm>;
    submit: void;
}
const props = defineProps<Props>();

const cardCls = "rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]";
const headerCls = "flex items-center gap-3 border-b border-gray-100 px-6 py-4 dark:border-[#2a2d38]";
const iconBadge = "flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-primary-500/10 text-primary-500";
const titleCls = "text-[15px] font-semibold text-gray-900 dark:text-white";
const subCls = "text-xs text-gray-400";
</script>
