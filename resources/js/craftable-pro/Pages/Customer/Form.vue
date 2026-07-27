<template>
    <Wizard :steps="steps" :processing="form.processing" :errors="form.errors" :field-step="fieldStep"
        submit-label="Save customer" max-width="3xl" @submit="submit">

        <!-- STEP 1 · IDENTITY -->
        <template #identity>
            <div class="space-y-5 p-6 sm:p-8">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Identity</h2>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <button type="button" @click="form.is_company = false" :class="optCardCls(!form.is_company)">
                        <span :class="radioCls(!form.is_company)"><span v-if="!form.is_company" class="h-2 w-2 rounded-full bg-white"></span></span>
                        <UserIcon class="h-5 w-5 text-primary-500" />
                        <span class="mt-1 block text-sm font-semibold text-gray-900 dark:text-white">Individual</span>
                        <span class="block text-xs text-gray-400">A person.</span>
                    </button>
                    <button type="button" @click="form.is_company = true" :class="optCardCls(form.is_company)">
                        <span :class="radioCls(form.is_company)"><span v-if="form.is_company" class="h-2 w-2 rounded-full bg-white"></span></span>
                        <BuildingOffice2Icon class="h-5 w-5 text-primary-500" />
                        <span class="mt-1 block text-sm font-semibold text-gray-900 dark:text-white">Company</span>
                        <span class="block text-xs text-gray-400">A business with a tax number.</span>
                    </button>
                </div>

                <div :class="rowCls">
                    <label :class="labelCls">Code</label>
                    <div class="w-full">
                        <input :value="form.code" readonly placeholder="Generated on save" :class="codeCls" />
                    </div>
                </div>

                <Field v-if="form.is_company" v-model="form.company_name" label="Company" :error="form.errors.company_name" placeholder="Company name" />
                <Field v-model="form.first_name" label="First name" :error="form.errors.first_name" />
                <Field v-model="form.last_name" label="Last name" :error="form.errors.last_name" />
                <Field v-model="form.tax_number" label="Tax number" :error="form.errors.tax_number" />
            </div>
        </template>

        <!-- STEP 2 · CONTACT -->
        <template #contact>
            <div class="space-y-5 p-6 sm:p-8">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Contact</h2>
                <Field v-model="form.phone" label="Phone" :error="form.errors.phone" type="tel" />
                <Field v-model="form.email" label="Email" :error="form.errors.email" type="email" />
                <Field v-model="form.billing_address" label="Address" :error="form.errors.billing_address" />
                <Field v-model="form.postal_code" label="Postal code" :error="form.errors.postal_code" />
                <div :class="rowCls">
                    <label :class="labelCls">City</label>
                    <div class="w-full">
                        <Multiselect v-model="form.city_id" name="city_id" mode="single"
                            :options="$page.props.cities ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                    </div>
                </div>
            </div>
        </template>

        <!-- STEP 3 · ACCOUNT & SETTINGS -->
        <template #account>
            <div class="space-y-6 p-6 sm:p-8">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Account &amp; settings</h2>

                <div class="space-y-2 rounded-xl bg-gray-50 p-4 dark:bg-[#171923]">
                    <div class="flex items-center justify-between text-sm"><span class="text-gray-400">Name</span><span class="font-medium text-gray-900 dark:text-white">{{ displayName }}</span></div>
                    <div class="flex items-center justify-between text-sm"><span class="text-gray-400">Type</span><span class="font-medium text-gray-900 dark:text-white">{{ form.is_company ? 'Company' : 'Individual' }}</span></div>
                </div>

                <Field v-model="form.username" label="Username" :error="form.errors.username" />
                <Field v-model="form.password" label="Password" :error="form.errors.password" type="password" />
                <Field v-model="form.credit" label="Credit" :error="form.errors.credit" type="number" />

                <div :class="rowCls">
                    <label :class="labelCls">Store</label>
                    <div class="w-full">
                        <Multiselect v-model="form.created_at_store_id" name="created_at_store_id" mode="single"
                            :options="$page.props.stores ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                    </div>
                </div>

                <div class="divide-y divide-gray-100 rounded-xl border border-gray-200 dark:divide-[#2a2d38] dark:border-[#2c2f3d]">
                    <Toggle v-model="form.is_active" title="Active" desc="Inactive customers can't be used in sales." />
                    <Toggle v-model="form.is_tax_exempted" title="Tax exempted" desc="No tax applied to this customer." />
                    <Toggle v-model="form.is_registered_online" title="Registered online" desc="Signed up via the storefront." />
                </div>

                <Field v-model="form.comments" label="Notes" :error="form.errors.comments" placeholder="Internal comments" />
            </div>
        </template>
    </Wizard>
</template>

<script setup lang="ts">
import { computed, h } from "vue";
import { UserIcon, BuildingOffice2Icon } from "@heroicons/vue/24/outline";
import { Multiselect } from "craftable-pro/Components";
import Wizard from "@/craftable-pro/Components/Wizard.vue";
import { InertiaForm } from "craftable-pro/types";
import type { CustomerForm } from "./types";

interface Props {
    form: InertiaForm<CustomerForm>;
    submit: () => void;
}
const props = defineProps<Props>();

const steps = [
    { key: "identity", label: "Identity" },
    { key: "contact", label: "Contact" },
    { key: "account", label: "Account" },
];
const fieldStep: Record<string, number> = {
    company_name: 0, first_name: 0, last_name: 0, tax_number: 0, code: 0, is_company: 0,
    phone: 1, email: 1, billing_address: 1, postal_code: 1, city_id: 1,
    username: 2, password: 2, credit: 2, created_at_store_id: 2, created_by: 2, last_login_time: 2,
    is_active: 2, is_tax_exempted: 2, is_registered_online: 2, comments: 2,
};

const displayName = computed(() => {
    if (props.form.is_company && props.form.company_name) return props.form.company_name;
    return [props.form.first_name, props.form.last_name].filter(Boolean).join(" ").trim() || "New customer";
});

const rowCls = "flex flex-col gap-1 sm:flex-row sm:items-center sm:gap-4";
const labelCls = "w-full text-sm font-medium text-gray-600 dark:text-gray-300 sm:w-32 sm:flex-shrink-0";
const inputCls = "w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 dark:border-[#2c2f3d] dark:bg-[#171923] dark:text-white";
const codeCls = "w-full cursor-not-allowed select-all rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium text-gray-700 focus:outline-none dark:border-[#2c2f3d] dark:bg-[#1a1c27] dark:text-gray-200";

const optCardCls = (active: boolean) =>
    "relative rounded-xl border p-4 text-left transition " +
    (active ? "border-primary-500 bg-primary-500/5 ring-1 ring-primary-500/30" : "border-gray-200 hover:border-gray-300 dark:border-[#2c2f3d]");
const radioCls = (active: boolean) =>
    "absolute right-3 top-3 flex h-4 w-4 items-center justify-center rounded-full border " +
    (active ? "border-primary-500 bg-primary-500" : "border-gray-300 dark:border-[#3a3e4d]");

// Inline labelled input row
const Field = (p: any, { emit }: any) =>
    h("div", { class: rowCls }, [
        h("label", { class: labelCls }, p.label),
        h("div", { class: "w-full" }, [
            h("input", {
                value: p.modelValue, type: p.type || "text", placeholder: p.placeholder, class: inputCls,
                onInput: (e: any) => emit("update:modelValue", e.target.value),
            }),
            p.error ? h("p", { class: "mt-1 text-xs text-red-500" }, p.error) : null,
        ]),
    ]);
(Field as any).props = ["modelValue", "label", "error", "type", "placeholder"];
(Field as any).emits = ["update:modelValue"];

// Toggle row
const Toggle = (p: any, { emit }: any) =>
    h("div", { class: "flex items-center justify-between gap-4 p-4" }, [
        h("div", {}, [
            h("p", { class: "text-sm font-medium text-gray-900 dark:text-white" }, p.title),
            h("p", { class: "text-xs text-gray-400" }, p.desc),
        ]),
        h("button", {
            type: "button",
            onClick: () => emit("update:modelValue", !p.modelValue),
            class: ["relative inline-flex h-6 w-11 flex-shrink-0 items-center rounded-full transition-colors focus:outline-none", p.modelValue ? "bg-primary-500" : "bg-gray-300 dark:bg-[#3a3d4d]"],
        }, [h("span", { class: ["inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform", p.modelValue ? "translate-x-6" : "translate-x-1"] })]),
    ]);
(Toggle as any).props = ["modelValue", "title", "desc"];
(Toggle as any).emits = ["update:modelValue"];
</script>
