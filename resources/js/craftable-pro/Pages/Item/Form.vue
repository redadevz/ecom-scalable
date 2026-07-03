<template>
    <PageContent>
        <div class="mx-auto max-w-6xl">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                <!-- Main column -->
                <div class="space-y-6 lg:col-span-2">
                    <Card title="Item details">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                            <TextInput v-model="form.name" name="name" label="Name" type="text" />
                            <TextInput v-model="form.sku_code" name="sku_code" label="SKU Code" type="text" />
                            <div class="sm:col-span-2">
                                <TextInput v-model="form.image" name="image" label="Image URL" type="text" placeholder="https://… or upload below" />
                            </div>
                            <div class="sm:col-span-2">
                                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-white">Upload image</label>
                                <div class="flex items-center gap-4">
                                    <div class="flex h-24 w-24 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg border border-dashed border-gray-300 bg-gray-50 dark:border-[#2c2f3d] dark:bg-white/5">
                                        <img v-if="form.image" :src="form.image" alt="preview" class="h-full w-full object-cover" />
                                        <PhotoIcon v-else class="h-8 w-8 text-gray-300" />
                                    </div>
                                    <div>
                                        <label class="inline-flex cursor-pointer items-center gap-2 rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-[#2c2f3d] dark:text-gray-200 dark:hover:bg-white/5">
                                            <ArrowUpTrayIcon class="h-4 w-4" />
                                            {{ uploading ? "Uploading…" : "Choose file" }}
                                            <input type="file" accept="image/*" class="hidden" @change="onFile" :disabled="uploading" />
                                        </label>
                                        <button v-if="form.image" type="button" @click="form.image = ''" class="ml-2 text-sm text-red-500 hover:text-red-600">Remove</button>
                                        <p class="mt-1 text-xs text-gray-400">PNG, JPG, GIF — up to 5 MB.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="sm:col-span-2">
                                <TextInput v-model="form.description" name="description" label="Description" type="text" />
                            </div>
                        </div>
                    </Card>

                    <Card title="Stock management">
                        <p class="-mt-2 mb-5 text-sm text-gray-500 dark:text-gray-400">
                            Quantities and low-stock thresholds. Leave blank for service items.
                        </p>
                        <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-3">
                            <TextInput v-model="form.current_stock_quantity" name="current_stock_quantity" label="Current Stock" type="number" />
                            <TextInput v-model="form.preferred_stock_quantity" name="preferred_stock_quantity" label="Preferred Stock" type="number" />
                            <TextInput v-model="form.min_stock_quantity" name="min_stock_quantity" label="Min Stock" type="number" />
                            <TextInput v-model="form.low_stock_quantity" name="low_stock_quantity" label="Low Stock Alert" type="number" />
                            <TextInput v-model="form.default_quantity" name="default_quantity" label="Default Quantity" type="number" />
                        </div>
                        <div class="mt-6 flex flex-wrap gap-x-10 gap-y-4 border-t border-gray-100 pt-5 dark:border-gray-800">
                            <Checkbox v-model="form.using_default_quantity" name="using_default_quantity" label="Using Default Quantity" />
                            <Checkbox v-model="form.low_stock_warning" name="low_stock_warning" label="Low Stock Warning" />
                        </div>
                    </Card>

                    <Card title="Notes">
                        <TextInput v-model="form.comments" name="comments" label="Comments" type="text" />
                    </Card>
                </div>

                <!-- Side panel -->
                <div class="space-y-6">
                    <Card title="Status">
                        <div class="space-y-4">
                            <Checkbox v-model="form.is_active" name="is_active" label="Active" />
                            <Checkbox v-model="form.is_service" name="is_service" label="Service item" />
                            <Checkbox v-model="form.in_stock" name="in_stock" label="In stock" />
                        </div>
                    </Card>

                    <Card title="Organization">
                        <div class="space-y-5">
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
                    </Card>
                </div>

            </div>
        </div>
    </PageContent>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { PhotoIcon, ArrowUpTrayIcon } from "@heroicons/vue/24/outline";
import {
    Card,
    TextInput,
    PageContent,
    Checkbox,
    Multiselect,
} from "craftable-pro/Components";
import { InertiaForm } from "craftable-pro/types";
import type { ItemForm } from "./types";

interface Props {
    form: InertiaForm<ItemForm>;
    submit: void;
}

const props = defineProps<Props>();

const uploading = ref(false);

const getCookie = (name: string) => {
    const m = document.cookie.match(new RegExp("(^| )" + name + "=([^;]+)"));
    return m ? decodeURIComponent(m[2]) : "";
};

const onFile = async (e: Event) => {
    const input = e.target as HTMLInputElement;
    const file = input.files?.[0];
    if (!file) return;

    const data = new FormData();
    data.append("file", file);
    uploading.value = true;
    try {
        const res = await fetch(route("craftable-pro.items.upload-image"), {
            method: "POST",
            body: data,
            credentials: "same-origin",
            headers: { "X-XSRF-TOKEN": getCookie("XSRF-TOKEN") },
        });
        const json = await res.json();
        if (json.url) {
            props.form.image = json.url;
        }
    } finally {
        uploading.value = false;
        input.value = "";
    }
};
</script>
