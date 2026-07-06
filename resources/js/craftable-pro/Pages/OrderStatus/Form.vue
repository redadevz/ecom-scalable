<template>
    <PageContent>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- ============ MAIN COLUMN ============ -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Details -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><ClipboardDocumentListIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Details</h3>
                            <p :class="subCls">Name and description shown across orders.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <TextInput
                            v-model="form.name"
                            name="name"
                            label="Name"
                            type="text"
                        />
                        <div class="sm:col-span-2">
                            <TextArea
                                v-model="form.description"
                                name="description"
                                label="Description"
                            />
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
                            <TruckIcon class="absolute -right-4 -top-4 h-32 w-32 text-white/15" />
                            <div v-if="form.is_default" class="absolute right-3 top-3 rounded-full bg-white/20 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-white backdrop-blur">
                                Default
                            </div>
                        </div>
                        <div class="px-5 pb-5">
                            <div class="-mt-12 mb-3 flex h-24 w-24 items-center justify-center rounded-2xl border-4 border-white bg-gradient-to-br from-primary-500 to-orange-600 text-3xl font-bold uppercase text-white shadow-lg ring-1 ring-black/5 dark:border-[#1e2029]">
                                {{ initials }}
                            </div>
                            <h3 class="truncate text-lg font-semibold text-gray-900 dark:text-white">{{ form.name || 'New Order Status' }}</h3>
                            <p class="mt-1 line-clamp-2 text-sm text-gray-400">{{ form.description || 'Description appears here as you type.' }}</p>
                        </div>
                    </section>

                    <!-- Flags -->
                    <section :class="cardCls">
                        <header :class="headerCls">
                            <span :class="iconBadge"><AdjustmentsHorizontalIcon class="h-5 w-5" /></span>
                            <h3 :class="titleCls">Flags</h3>
                        </header>
                        <div class="divide-y divide-gray-100 dark:divide-[#2a2d38]">
                            <ToggleRow v-model="form.is_default" title="Default" desc="Use this status as the default for new orders." />
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
    ClipboardDocumentListIcon,
    AdjustmentsHorizontalIcon,
    TruckIcon,
} from "@heroicons/vue/24/outline";
import {
    TextInput,
    TextArea,
    PageContent,
} from "craftable-pro/Components";
import { InertiaForm } from "craftable-pro/types";
import type { OrderStatusForm } from "./types";

interface Props {
    form: InertiaForm<OrderStatusForm>;
    submit: void;
}
const props = defineProps<Props>();

const cardCls = "rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]";
const headerCls = "flex items-center gap-3 border-b border-gray-100 px-6 py-4 dark:border-[#2a2d38]";
const iconBadge = "flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-primary-500/10 text-primary-500";
const titleCls = "text-[15px] font-semibold text-gray-900 dark:text-white";
const subCls = "text-xs text-gray-400";

const initials = computed(() => {
    const parts = (props.form.name || "").toString().trim().split(/\s+/).filter(Boolean);
    if (!parts.length) return "OS";
    return (parts[0][0] + (parts[1]?.[0] ?? "")).toUpperCase();
});

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
