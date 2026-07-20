<template>
    <PageContent>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- ============ MAIN COLUMN ============ -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Identity -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><IdentificationIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Identity</h3>
                            <p :class="subCls">Who the supplier is and their tax registration.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <AutoCodeInput :model-value="form.code" label="Code" />
                        <TextInput v-model="form.company_name" name="company_name" label="Company Name" type="text" />
                        <TextInput v-model="form.first_name" name="first_name" label="First Name" type="text" />
                        <TextInput v-model="form.last_name" name="last_name" label="Last Name" type="text" />
                        <TextInput v-model="form.tax_number" name="tax_number" label="Tax Number" type="text" />
                    </div>
                </section>

                <!-- Contact -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><PhoneIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Contact</h3>
                            <p :class="subCls">Phone, email and billing location.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <TextInput v-model="form.phone" name="phone" label="Phone" type="text" />
                        <TextInput v-model="form.email" name="email" label="Email" type="text" />
                        <TextInput v-model="form.billing_address" name="billing_address" label="Billing Address" type="text" />
                        <TextInput v-model="form.postal_code" name="postal_code" label="Postal Code" type="text" />
                    </div>
                </section>

                <!-- Notes -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><PencilSquareIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Notes</h3>
                            <p :class="subCls">Internal comments about this supplier.</p>
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
                            <component :is="form.is_company ? BuildingOffice2Icon : UserIcon" class="absolute -right-4 -top-4 h-32 w-32 text-white/15" />
                            <div class="absolute right-3 top-3 rounded-full bg-white/20 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-white backdrop-blur">
                                {{ form.is_company ? 'Company' : 'Individual' }}
                            </div>
                        </div>
                        <div class="px-5 pb-5">
                            <div class="-mt-12 mb-3 flex h-24 w-24 items-center justify-center rounded-2xl border-4 border-white bg-gradient-to-br from-primary-500 to-orange-600 text-3xl font-bold uppercase text-white shadow-lg ring-1 ring-black/5 dark:border-[#1e2029]">
                                {{ initials }}
                            </div>
                            <div class="flex items-center gap-2">
                                <h3 class="truncate text-lg font-semibold text-gray-900 dark:text-white">{{ displayName }}</h3>
                                <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium"
                                    :class="form.is_active ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-gray-100 text-gray-500 dark:bg-white/5 dark:text-gray-400'">
                                    <span class="h-1.5 w-1.5 rounded-full" :class="form.is_active ? 'bg-green-500' : 'bg-gray-400'"></span>
                                    {{ form.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-400">{{ form.code || '—' }}</p>

                            <dl class="mt-4 space-y-2 border-t border-gray-100 pt-4 text-sm dark:border-[#2a2d38]">
                                <div v-if="form.email" class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                                    <EnvelopeIcon class="h-4 w-4 flex-shrink-0 text-gray-400" /><span class="truncate">{{ form.email }}</span>
                                </div>
                                <div v-if="form.phone" class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                                    <PhoneIcon class="h-4 w-4 flex-shrink-0 text-gray-400" /><span class="truncate">{{ form.phone }}</span>
                                </div>
                                <div v-if="form.billing_address" class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                                    <MapPinIcon class="h-4 w-4 flex-shrink-0 text-gray-400" /><span class="truncate">{{ form.billing_address }}</span>
                                </div>
                                <p v-if="!form.email && !form.phone && !form.billing_address" class="text-xs text-gray-400">Contact details appear here as you fill them in.</p>
                            </dl>
                        </div>
                    </section>

                    <!-- Flags -->
                    <section :class="cardCls">
                        <header :class="headerCls">
                            <span :class="iconBadge"><AdjustmentsHorizontalIcon class="h-5 w-5" /></span>
                            <h3 :class="titleCls">Flags</h3>
                        </header>
                        <div class="divide-y divide-gray-100 dark:divide-[#2a2d38]">
                            <ToggleRow v-model="form.is_active" title="Active" desc="Inactive suppliers are hidden from operations." />
                            <ToggleRow v-model="form.is_company" title="Company" desc="Treat this supplier as a company." />
                            <ToggleRow v-model="form.is_tax_exempted" title="Tax exempted" desc="No tax applied to this supplier." />
                        </div>
                    </section>

                    <!-- Relations -->
                    <section :class="cardCls">
                        <header :class="headerCls">
                            <span :class="iconBadge"><LinkIcon class="h-5 w-5" /></span>
                            <h3 :class="titleCls">Relations</h3>
                        </header>
                        <div class="space-y-4 p-5">
                            <Multiselect v-model="form.store_id" name="store_id" label="Store" mode="single"
                                :options="$page.props.stores ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                            <Multiselect v-model="form.city_id" name="city_id" label="City" mode="single"
                                :options="$page.props.cities ?? []" options-value-prop="id" options-label="name" :searchable="true" />
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
import { computed, h } from "vue";
import {
    IdentificationIcon,
    PhoneIcon,
    PencilSquareIcon,
    EnvelopeIcon,
    MapPinIcon,
    AdjustmentsHorizontalIcon,
    LinkIcon,
    BuildingOffice2Icon,
    UserIcon,
} from "@heroicons/vue/24/outline";
import {
    TextInput,
    PageContent,
    Multiselect,
} from "craftable-pro/Components";
import AutoCodeInput from "@/craftable-pro/Components/AutoCodeInput.vue";
import { InertiaForm } from "craftable-pro/types";
import type { SupplierForm } from "./types";

interface Props {
    form: InertiaForm<SupplierForm>;
    submit: void;
}
const props = defineProps<Props>();

const cardCls = "rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]";
const headerCls = "flex items-center gap-3 border-b border-gray-100 px-6 py-4 dark:border-[#2a2d38]";
const iconBadge = "flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-primary-500/10 text-primary-500";
const titleCls = "text-[15px] font-semibold text-gray-900 dark:text-white";
const subCls = "text-xs text-gray-400";

const displayName = computed(() => {
    if (props.form.is_company && props.form.company_name) return props.form.company_name;
    const full = [props.form.first_name, props.form.last_name].filter(Boolean).join(" ").trim();
    return full || props.form.company_name || "New Supplier";
});

const initials = computed(() => {
    const base = props.form.is_company
        ? (props.form.company_name || "")
        : [props.form.first_name, props.form.last_name].filter(Boolean).join(" ");
    const parts = base.toString().trim().split(/\s+/).filter(Boolean);
    if (!parts.length) return (props.form.code || "S").toString().slice(0, 2).toUpperCase();
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
