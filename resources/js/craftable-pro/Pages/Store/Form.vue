<template>
    <Wizard :steps="steps" :processing="form.processing" :errors="form.errors" :field-step="fieldStep"
        submit-label="Save store" max-width="3xl" @submit="submit">

        <!-- STEP 1 · IDENTITY -->
        <template #identity>
            <div class="space-y-5 p-6 sm:p-8">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Store identity</h2>

                <!-- logo upload -->
                <div class="flex items-center gap-4">
                    <button type="button" @click="pickLogo"
                        class="group relative flex h-20 w-20 flex-shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-gradient-to-br from-primary-500 to-orange-600 text-2xl font-bold uppercase text-white shadow">
                        <img v-if="logoIsUrl" :src="form.logo" alt="logo" class="h-full w-full object-cover" />
                        <template v-else>{{ initials }}</template>
                        <span class="absolute inset-0 flex items-center justify-center bg-black/50 opacity-0 transition-opacity group-hover:opacity-100"><CameraIcon class="h-5 w-5" /></span>
                        <span v-if="uploading" class="absolute inset-0 flex items-center justify-center bg-black/60"><ArrowPathIcon class="h-5 w-5 animate-spin" /></span>
                    </button>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Logo</p>
                        <p class="text-xs text-gray-400">Click the tile to upload · PNG/JPG/WEBP up to 5&nbsp;MB</p>
                        <button v-if="logoIsUrl" type="button" @click="form.logo = ''" class="mt-1 text-xs text-gray-400 hover:text-red-500">Remove</button>
                        <p v-if="uploadError" class="mt-1 text-xs text-red-500">{{ uploadError }}</p>
                    </div>
                    <input ref="logoInput" type="file" accept="image/*" class="hidden" @change="onLogoChange" />
                </div>

                <div :class="rowCls">
                    <label :class="labelCls">Code</label>
                    <div class="w-full">
                        <input :value="form.code" readonly placeholder="Generated on save" :class="codeCls" />
                    </div>
                </div>

                <Field v-model="form.name" label="Name" :error="form.errors.name" placeholder="Store name" />
                <Field v-model="form.legal_entity_name" label="Legal entity" :error="form.errors.legal_entity_name" />
                <Field v-model="form.tax_code" label="Tax code" :error="form.errors.tax_code" />
                <Field v-model="form.registration_number" label="Reg. number" :error="form.errors.registration_number" />
            </div>
        </template>

        <!-- STEP 2 · CONTACT -->
        <template #contact>
            <div class="space-y-5 p-6 sm:p-8">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Contact</h2>
                <Field v-model="form.phone" label="Phone" :error="form.errors.phone" type="tel" />
                <Field v-model="form.fax" label="Fax" :error="form.errors.fax" />
                <Field v-model="form.email" label="Email" :error="form.errors.email" type="email" />
                <Field v-model="form.address" label="Address" :error="form.errors.address" />
            </div>
        </template>

        <!-- STEP 3 · BANKING & LOCALIZATION -->
        <template #settings>
            <div class="space-y-6 p-6 sm:p-8">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Banking &amp; localization</h2>

                <div class="space-y-2 rounded-xl bg-gray-50 p-4 dark:bg-[#171923]">
                    <div class="flex items-center justify-between text-sm"><span class="text-gray-400">Store</span><span class="font-medium text-gray-900 dark:text-white">{{ form.name || '—' }}</span></div>
                </div>

                <Field v-model="form.bank_branch" label="Bank branch" :error="form.errors.bank_branch" />
                <Field v-model="form.bank_code" label="Bank code" :error="form.errors.bank_code" />
                <Field v-model="form.bank_account" label="Bank account" :error="form.errors.bank_account" />

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                    <Multiselect v-model="form.city_id" name="city_id" label="City" mode="single"
                        :options="$page.props.cities ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                    <Multiselect v-model="form.language_id" name="language_id" label="Language" mode="single"
                        :options="$page.props.languages ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                    <Multiselect v-model="form.currency_id" name="currency_id" label="Currency" mode="single"
                        :options="$page.props.currencies ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                </div>

                <div class="flex items-center justify-between gap-4 rounded-xl border border-gray-200 p-4 dark:border-[#2c2f3d]">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Active</p>
                        <p class="text-xs text-gray-400">Inactive stores are hidden from operations.</p>
                    </div>
                    <button type="button" @click="form.is_active = !form.is_active"
                        class="relative inline-flex h-6 w-11 flex-shrink-0 items-center rounded-full transition-colors focus:outline-none"
                        :class="form.is_active ? 'bg-primary-500' : 'bg-gray-300 dark:bg-[#3a3d4d]'">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform" :class="form.is_active ? 'translate-x-6' : 'translate-x-1'"></span>
                    </button>
                </div>
            </div>
        </template>
    </Wizard>
</template>

<script setup lang="ts">
import { computed, h, ref } from "vue";
import axios from "axios";
import { CameraIcon, ArrowPathIcon } from "@heroicons/vue/24/outline";
import { Multiselect } from "craftable-pro/Components";
import Wizard from "@/craftable-pro/Components/Wizard.vue";
import { InertiaForm } from "craftable-pro/types";
import type { StoreForm } from "./types";

interface Props {
    form: InertiaForm<StoreForm>;
    submit: () => void;
}
const props = defineProps<Props>();

const steps = [
    { key: "identity", label: "Identity" },
    { key: "contact", label: "Contact" },
    { key: "settings", label: "Banking & locale" },
];
const fieldStep: Record<string, number> = {
    name: 0, code: 0, legal_entity_name: 0, tax_code: 0, registration_number: 0, logo: 0,
    phone: 1, fax: 1, email: 1, address: 1,
    bank_branch: 2, bank_code: 2, bank_account: 2, city_id: 2, language_id: 2, currency_id: 2, is_active: 2,
};

const initials = computed(() => (props.form.code || props.form.name || "S").toString().trim().slice(0, 2).toUpperCase());
const logoIsUrl = computed(() => {
    const v = (props.form.logo || "").toString();
    return /^https?:\/\//i.test(v) || v.startsWith("/");
});

// ---- logo upload (unchanged) ----
const logoInput = ref<HTMLInputElement | null>(null);
const uploading = ref(false);
const uploadError = ref("");
const pickLogo = () => logoInput.value?.click();
const onLogoChange = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) uploadLogo(file);
    (e.target as HTMLInputElement).value = "";
};
const uploadLogo = async (file: File) => {
    uploadError.value = "";
    if (!file.type.startsWith("image/")) { uploadError.value = "Please choose an image file."; return; }
    if (file.size > 5 * 1024 * 1024) { uploadError.value = "Image must be 5 MB or smaller."; return; }
    uploading.value = true;
    try {
        const data = new FormData();
        data.append("logo", file);
        const res = await axios.post(route("craftable-pro.stores.upload-logo"), data, { headers: { "Content-Type": "multipart/form-data" } });
        props.form.logo = res.data.url;
    } catch (err: any) {
        uploadError.value = err?.response?.data?.message ?? "Upload failed. Try again.";
    } finally {
        uploading.value = false;
    }
};

const rowCls = "flex flex-col gap-1 sm:flex-row sm:items-center sm:gap-4";
const labelCls = "w-full text-sm font-medium text-gray-600 dark:text-gray-300 sm:w-32 sm:flex-shrink-0";
const inputCls = "w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 dark:border-[#2c2f3d] dark:bg-[#171923] dark:text-white";
const codeCls = "w-full cursor-not-allowed select-all rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium text-gray-700 focus:outline-none dark:border-[#2c2f3d] dark:bg-[#1a1c27] dark:text-gray-200";

const Field = (p: any, { emit }: any) =>
    h("div", { class: rowCls }, [
        h("label", { class: labelCls }, p.label),
        h("div", { class: "w-full" }, [
            h("input", { value: p.modelValue, type: p.type || "text", placeholder: p.placeholder, class: inputCls, onInput: (e: any) => emit("update:modelValue", e.target.value) }),
            p.error ? h("p", { class: "mt-1 text-xs text-red-500" }, p.error) : null,
        ]),
    ]);
(Field as any).props = ["modelValue", "label", "error", "type", "placeholder"];
(Field as any).emits = ["update:modelValue"];
</script>
