<template>
    <PageContent>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- ============ MAIN COLUMN ============ -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Document -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><DocumentTextIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Document</h3>
                            <p :class="subCls">Reference numbers and descriptive details.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <TextInput v-model="form.number" name="number" label="Number" type="text" />
                        <TextInput v-model="form.external_number" name="external_number" label="External Number" type="text" />
                        <div class="sm:col-span-2">
                            <TextInput v-model="form.description" name="description" label="Description" type="text" />
                        </div>
                        <div class="sm:col-span-2">
                            <TextInput v-model="form.comments" name="comments" label="Comments" type="text" />
                        </div>
                    </div>
                </section>

                <!-- Source references -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><ArrowsRightLeftIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Source References</h3>
                            <p :class="subCls">Link this document to its originating operation.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <Multiselect v-model="form.sale_order_id" name="sale_order_id" label="Sale Order" mode="single"
                            :options="$page.props.order_headers ?? []" options-value-prop="id" options-label="order_no" :searchable="true" />
                        <Multiselect v-model="form.purchase_id" name="purchase_id" label="Purchase" mode="single"
                            :options="$page.props.purchases ?? []" options-value-prop="id" options-label="description" :searchable="true" />
                        <Multiselect v-model="form.stock_return_id" name="stock_return_id" label="Stock Return" mode="single"
                            :options="$page.props.stock_returns ?? []" options-value-prop="id" options-label="description" :searchable="true" />
                        <Multiselect v-model="form.inventory_count_id" name="inventory_count_id" label="Inventory Count" mode="single"
                            :options="$page.props.inventory_counts ?? []" options-value-prop="id" options-label="description" :searchable="true" />
                        <Multiselect v-model="form.loss_and_damage_id" name="loss_and_damage_id" label="Loss And Damage" mode="single"
                            :options="$page.props.loss_and_damages ?? []" options-value-prop="id" options-label="description" :searchable="true" />
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
                            <Multiselect v-model="form.document_type_id" name="document_type_id" label="Document Type" mode="single"
                                :options="$page.props.document_types ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                            <Multiselect v-model="form.created_by" name="created_by" label="Created By" mode="single"
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
    DocumentTextIcon,
    ArrowsRightLeftIcon,
    LinkIcon,
} from "@heroicons/vue/24/outline";
import {
    TextInput,
    PageContent,
    Multiselect,
} from "craftable-pro/Components";
import { InertiaForm } from "craftable-pro/types";
import type { DocumentForm } from "./types";

interface Props {
    form: InertiaForm<DocumentForm>;
    submit: void;
}
defineProps<Props>();

const cardCls = "rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]";
const headerCls = "flex items-center gap-3 border-b border-gray-100 px-6 py-4 dark:border-[#2a2d38]";
const iconBadge = "flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-primary-500/10 text-primary-500";
const titleCls = "text-[15px] font-semibold text-gray-900 dark:text-white";
const subCls = "text-xs text-gray-400";
</script>
