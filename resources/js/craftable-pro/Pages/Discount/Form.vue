<template>
    <PageContent>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- ============ MAIN COLUMN ============ -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Discount -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><TagIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Discount</h3>
                            <p :class="subCls">Describe the discount and add internal notes.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <TextInput v-model="form.description" name="description" label="Description" type="text" />
                        </div>
                        <div class="sm:col-span-2">
                            <TextArea v-model="form.comments" name="comments" label="Comments" />
                        </div>
                    </div>
                </section>
            </div>

            <!-- ============ SIDE COLUMN ============ -->
            <div class="space-y-6">
                <div class="lg:sticky lg:top-24 space-y-6">
                    <!-- Live preview -->
                    <section class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]">
                        <div class="relative h-28 bg-gradient-to-br from-primary-500 via-orange-500 to-amber-400">
                            <TagIcon class="absolute -right-4 -top-4 h-32 w-32 text-white/15" />
                        </div>
                        <div class="px-5 pb-5">
                            <div class="-mt-12 mb-3 flex h-24 w-24 items-center justify-center rounded-2xl border-4 border-white bg-gradient-to-br from-primary-500 to-orange-600 text-3xl font-bold uppercase text-white shadow-lg ring-1 ring-black/5 dark:border-[#1e2029]">
                                {{ initials }}
                            </div>
                            <h3 class="truncate text-lg font-semibold text-gray-900 dark:text-white">{{ displayName }}</h3>
                            <p v-if="form.comments" class="mt-1 line-clamp-2 text-sm text-gray-400">{{ form.comments }}</p>
                            <p v-else class="mt-1 text-xs text-gray-400">Relations and notes appear here as you fill them in.</p>
                        </div>
                    </section>

                    <!-- Relations -->
                    <section :class="cardCls">
                        <header :class="headerCls">
                            <span :class="iconBadge"><LinkIcon class="h-5 w-5" /></span>
                            <h3 :class="titleCls">Relations</h3>
                        </header>
                        <div class="space-y-4 p-5">
                            <Multiselect v-model="form.discount_type_id" name="discount_type_id" label="Discount Type" mode="single"
                                :options="$page.props.discount_types ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                            <Multiselect v-model="form.item_category_id" name="item_category_id" label="Item Category" mode="single"
                                :options="$page.props.item_categories ?? []" options-value-prop="id" options-label="name" :searchable="true" />
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
import { computed } from "vue";
import {
    TagIcon,
    LinkIcon,
} from "@heroicons/vue/24/outline";
import {
    TextInput,
    TextArea,
    PageContent,
    Multiselect,
} from "craftable-pro/Components";
import { InertiaForm } from "craftable-pro/types";
import type { DiscountForm } from "./types";

interface Props {
    form: InertiaForm<DiscountForm>;
    submit: void;
}
const props = defineProps<Props>();

const cardCls = "rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]";
const headerCls = "flex items-center gap-3 border-b border-gray-100 px-6 py-4 dark:border-[#2a2d38]";
const iconBadge = "flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-primary-500/10 text-primary-500";
const titleCls = "text-[15px] font-semibold text-gray-900 dark:text-white";
const subCls = "text-xs text-gray-400";

const displayName = computed(() => {
    const desc = (props.form.description || "").toString().trim();
    return desc || "New Discount";
});

const initials = computed(() => {
    const parts = (props.form.description || "").toString().trim().split(/\s+/).filter(Boolean);
    if (!parts.length) return "DI";
    return (parts[0][0] + (parts[1]?.[0] ?? "")).toUpperCase();
});
</script>
