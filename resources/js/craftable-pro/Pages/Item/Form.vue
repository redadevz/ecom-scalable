<template>
    <PageContent>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- ============ MAIN COLUMN ============ -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Item details -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><CubeIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Item details</h3>
                            <p :class="subCls">Name, code and how it appears in the catalog.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <TextInput v-model="form.name" name="name" label="Name" type="text" />
                        <AutoCodeInput :model-value="form.sku_code" label="SKU Code" />
                        <div class="sm:col-span-2">
                            <Dropzone v-model="form.images" name="images" label="Images" :max-file-size="5 * 1024 * 1024" />
                        </div>
                        <div class="sm:col-span-2">
                            <TextInput v-model="form.description" name="description" label="Description" type="text" />
                        </div>
                    </div>
                </section>

                <!-- Stock management -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><ArchiveBoxIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Stock management</h3>
                            <p :class="subCls">Quantities and low-stock thresholds. Leave blank for service items.</p>
                        </div>
                    </header>
                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-3">
                            <TextInput v-model="form.current_stock_quantity" name="current_stock_quantity" label="Current Stock" type="number" />
                            <TextInput v-model="form.preferred_stock_quantity" name="preferred_stock_quantity" label="Preferred Stock" type="number" />
                            <TextInput v-model="form.min_stock_quantity" name="min_stock_quantity" label="Min Stock" type="number" />
                            <TextInput v-model="form.low_stock_quantity" name="low_stock_quantity" label="Low Stock Alert" type="number" />
                            <TextInput v-model="form.default_quantity" name="default_quantity" label="Default Quantity" type="number" />
                        </div>
                        <div class="mt-6 flex flex-wrap gap-x-8 gap-y-4 border-t border-gray-100 pt-6 dark:border-[#2a2d38]">
                            <Checkbox v-model="form.using_default_quantity" name="using_default_quantity" label="Using Default Quantity" />
                            <Checkbox v-model="form.low_stock_warning" name="low_stock_warning" label="Low Stock Warning" />
                        </div>
                    </div>
                </section>

                <!-- Notes -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><PencilSquareIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Notes</h3>
                            <p :class="subCls">Internal comments — not shown to customers.</p>
                        </div>
                    </header>
                    <div class="p-6">
                        <TextInput v-model="form.comments" name="comments" label="Comments" type="text" />
                    </div>
                </section>
            </div>

            <!-- ============ SIDE PANEL ============ -->
            <div class="space-y-6">
                <!-- Live preview -->
                <section :class="cardCls">
                    <div class="p-6">
                        <div class="relative mx-auto flex h-40 w-full items-center justify-center overflow-hidden rounded-xl bg-gray-50 dark:bg-[#171923]">
                            <img v-if="previewImage" :src="previewImage" alt="" class="h-full w-full object-contain" />
                            <span v-else class="text-4xl font-bold text-primary-500/30">{{ initial }}</span>
                            <span class="absolute right-2 top-2 inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[11px] font-semibold"
                                :class="form.is_active ? 'bg-green-500/15 text-green-600 dark:text-green-400' : 'bg-gray-400/15 text-gray-500'">
                                <span class="h-1.5 w-1.5 rounded-full" :class="form.is_active ? 'bg-green-500' : 'bg-gray-400'"></span>
                                {{ form.is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <p class="mt-4 truncate text-center text-base font-semibold text-gray-900 dark:text-white">{{ form.name || 'Untitled item' }}</p>
                        <p class="text-center text-xs text-gray-400">{{ form.sku_code || 'SKU on save' }}</p>

                        <div class="mt-4 flex items-center justify-between rounded-xl bg-primary-500/5 px-4 py-3 ring-1 ring-primary-500/10">
                            <span class="text-xs font-medium uppercase tracking-wide text-gray-400">Current stock</span>
                            <span class="text-lg font-bold" :class="stockClass">{{ stockLabel }}</span>
                        </div>
                    </div>
                </section>

                <!-- Status -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><CheckBadgeIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Status</h3>
                            <p :class="subCls">Visibility and item type.</p>
                        </div>
                    </header>
                    <div class="space-y-4 p-6">
                        <Checkbox v-model="form.is_active" name="is_active" label="Active" />
                        <Checkbox v-model="form.is_service" name="is_service" label="Service item" />
                        <Checkbox v-model="form.in_stock" name="in_stock" label="In stock" />
                    </div>
                </section>

                <!-- Organization -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><Squares2X2Icon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Organization</h3>
                            <p :class="subCls">Category, supplier and where it lives.</p>
                        </div>
                    </header>
                    <div class="space-y-5 p-6">
                        <Multiselect
                            v-model="form.item_category_id" name="item_category_id" label="Category" mode="single"
                            :options="$page.props.item_categories ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                        <Multiselect
                            v-model="form.supplier_id" name="supplier_id" label="Supplier" mode="single"
                            :options="$page.props.suppliers ?? []" options-value-prop="id" options-label="code" :searchable="true" />
                        <Multiselect
                            v-model="form.measure_unit_id" name="measure_unit_id" label="Measure Unit" mode="single"
                            :options="$page.props.measure_units ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                        <Multiselect
                            v-model="form.store_id" name="store_id" label="Store" mode="single"
                            :options="$page.props.stores ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                    </div>
                </section>
            </div>
        </div>
    </PageContent>
</template>

<script setup lang="ts">
import { computed } from "vue";
import {
    TextInput,
    PageContent,
    Checkbox,
    Multiselect,
    Dropzone,
} from "craftable-pro/Components";
import {
    CubeIcon,
    ArchiveBoxIcon,
    PencilSquareIcon,
    CheckBadgeIcon,
    Squares2X2Icon,
} from "@heroicons/vue/24/outline";
import AutoCodeInput from "@/craftable-pro/Components/AutoCodeInput.vue";
import { InertiaForm } from "craftable-pro/types";
import type { ItemForm } from "./types";

interface Props {
    form: InertiaForm<ItemForm>;
    submit: void;
}
const props = defineProps<Props>();

const cardCls = "rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]";
const headerCls = "flex items-center gap-3 border-b border-gray-100 px-6 py-4 dark:border-[#2a2d38]";
const iconBadge = "flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-primary-500/10 text-primary-500";
const titleCls = "text-[15px] font-semibold text-gray-900 dark:text-white";
const subCls = "text-xs text-gray-400";

const initial = computed(() => (props.form.name || "?").toString().trim().charAt(0).toUpperCase() || "?");

// first newly-selected image (Dropzone holds File objects) → object URL for preview
const previewImage = computed(() => {
    const imgs: any = props.form.images;
    const first = Array.isArray(imgs) ? imgs[0] : null;
    if (first instanceof File) return URL.createObjectURL(first);
    if (first && typeof first === "object" && first.url) return first.url;
    return null;
});

const stockLabel = computed(() => {
    if (props.form.is_service) return "—";
    const q = Number(props.form.current_stock_quantity ?? 0);
    return Number.isFinite(q) ? q.toLocaleString("en-US") : "0";
});
const stockClass = computed(() => {
    if (props.form.is_service) return "text-gray-400";
    const q = Number(props.form.current_stock_quantity ?? 0);
    const low = Number(props.form.low_stock_quantity ?? 0);
    if (q <= 0) return "text-red-500";
    if (low > 0 && q <= low) return "text-amber-500";
    return "text-primary-600 dark:text-primary-400";
});
</script>
