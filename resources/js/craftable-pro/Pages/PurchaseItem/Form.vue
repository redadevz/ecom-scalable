<template>
    <PageContent>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- ============ MAIN COLUMN ============ -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Line -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><ArchiveBoxIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Line</h3>
                            <p :class="subCls">Quantity purchased and any returned units.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <TextInput v-model="form.quantity" name="quantity" label="Quantity" type="number" />
                        <TextInput v-model="form.return_amount" name="return_amount" label="Return Amount" type="number" />
                        <div class="sm:col-span-2">
                            <TextInput v-model="form.description" name="description" label="Description" type="text" />
                        </div>
                    </div>
                </section>

                <!-- Amounts -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><BanknotesIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Amounts</h3>
                            <p :class="subCls">Supplier pricing, tax and discount for this line.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <TextInput v-model="form.supplier_price_before_tax" name="supplier_price_before_tax" label="Supplier Price Before Tax" type="number" />
                        <TextInput v-model="form.supplier_tax_value" name="supplier_tax_value" label="Supplier Tax Value" type="number" />
                        <TextInput v-model="form.supplier_price_after_tax" name="supplier_price_after_tax" label="Supplier Price After Tax" type="number" />
                        <TextInput v-model="form.supplier_discount_value" name="supplier_discount_value" label="Supplier Discount Value" type="number" />
                    </div>
                </section>

                <!-- Notes -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><PencilSquareIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Notes</h3>
                            <p :class="subCls">Internal comments about this purchase line.</p>
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
                    <!-- Relations -->
                    <section :class="cardCls">
                        <header :class="headerCls">
                            <span :class="iconBadge"><LinkIcon class="h-5 w-5" /></span>
                            <h3 :class="titleCls">Relations</h3>
                        </header>
                        <div class="space-y-4 p-5">
                            <Multiselect v-model="form.purchase_id" name="purchase_id" label="Purchase" mode="single"
                                :options="$page.props.purchases ?? []" options-value-prop="id" options-label="description" :searchable="true" />
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
    ArchiveBoxIcon,
    BanknotesIcon,
    PencilSquareIcon,
    LinkIcon,
} from "@heroicons/vue/24/outline";
import {
    TextInput,
    PageContent,
    Multiselect,
} from "craftable-pro/Components";
import { InertiaForm } from "craftable-pro/types";
import type { PurchaseItemForm } from "./types";

interface Props {
    form: InertiaForm<PurchaseItemForm>;
    submit: void;
}
defineProps<Props>();

const cardCls = "rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]";
const headerCls = "flex items-center gap-3 border-b border-gray-100 px-6 py-4 dark:border-[#2a2d38]";
const iconBadge = "flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-primary-500/10 text-primary-500";
const titleCls = "text-[15px] font-semibold text-gray-900 dark:text-white";
const subCls = "text-xs text-gray-400";
</script>
