<template>
    <PageContent>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- ============ MAIN COLUMN ============ -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Store identity -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><BuildingStorefrontIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Store Identity</h3>
                            <p :class="subCls">Name, code and legal registration details.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <TextInput v-model="form.code" name="code" label="Code" type="text" />
                        <TextInput v-model="form.name" name="name" label="Name" type="text" />
                        <TextInput v-model="form.legal_entity_name" name="legal_entity_name" label="Legal Entity Name" type="text" />
                        <TextInput v-model="form.tax_code" name="tax_code" label="Tax Code" type="text" />
                        <div class="sm:col-span-2">
                            <TextInput v-model="form.registration_number" name="registration_number" label="Registration Number" type="text" />
                        </div>
                    </div>
                </section>

                <!-- Contact -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><PhoneIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Contact</h3>
                            <p :class="subCls">How customers and suppliers reach this store.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <TextInput v-model="form.phone" name="phone" label="Phone" type="text" />
                        <TextInput v-model="form.fax" name="fax" label="Fax" type="text" />
                        <div class="sm:col-span-2">
                            <TextInput v-model="form.email" name="email" label="Email" type="text" />
                        </div>
                        <div class="sm:col-span-2">
                            <TextInput v-model="form.address" name="address" label="Address" type="text" />
                        </div>
                    </div>
                </section>

                <!-- Banking -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><BuildingLibraryIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Banking</h3>
                            <p :class="subCls">Bank details used on invoices and payouts.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <TextInput v-model="form.bank_branch" name="bank_branch" label="Bank Branch" type="text" />
                        <TextInput v-model="form.bank_code" name="bank_code" label="Bank Code" type="text" />
                        <div class="sm:col-span-2">
                            <TextInput v-model="form.bank_account" name="bank_account" label="Bank Account" type="text" />
                        </div>
                    </div>
                </section>
            </div>

            <!-- ============ SIDE COLUMN ============ -->
            <div class="space-y-6">
                <div class="lg:sticky lg:top-24 space-y-6">
                    <!-- Live preview card -->
                    <section class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]">
                        <!-- gradient header with decorative watermark -->
                        <div class="relative h-28 bg-gradient-to-br from-primary-500 via-orange-500 to-amber-400">
                            <BuildingStorefrontIcon class="absolute -right-4 -top-4 h-32 w-32 text-white/15" />
                            <div class="absolute right-3 top-3 rounded-full bg-white/20 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-white backdrop-blur">
                                Store
                            </div>
                        </div>

                        <div class="px-5 pb-5">
                            <!-- avatar with camera overlay (click to upload) -->
                            <button type="button" @click="pickLogo"
                                class="group relative -mt-12 mb-3 flex h-24 w-24 items-center justify-center overflow-hidden rounded-2xl border-4 border-white bg-gradient-to-br from-primary-500 to-orange-600 text-3xl font-bold uppercase text-white shadow-lg ring-1 ring-black/5 dark:border-[#1e2029]">
                                <img v-if="logoIsUrl" :src="form.logo" :alt="form.name" class="h-full w-full object-cover" />
                                <template v-else>{{ initials }}</template>
                                <span class="absolute inset-0 flex items-center justify-center bg-black/50 opacity-0 transition-opacity group-hover:opacity-100">
                                    <CameraIcon class="h-6 w-6 text-white" />
                                </span>
                                <span v-if="uploading" class="absolute inset-0 flex items-center justify-center bg-black/60">
                                    <ArrowPathIcon class="h-6 w-6 animate-spin text-white" />
                                </span>
                            </button>

                            <div class="flex items-center gap-2">
                                <h3 class="truncate text-lg font-semibold text-gray-900 dark:text-white">{{ form.name || 'New Store' }}</h3>
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
                                <div v-if="form.address" class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                                    <MapPinIcon class="h-4 w-4 flex-shrink-0 text-gray-400" /><span class="truncate">{{ form.address }}</span>
                                </div>
                                <p v-if="!form.email && !form.phone && !form.address" class="text-xs text-gray-400">Contact details appear here as you fill them in.</p>
                            </dl>
                        </div>
                    </section>

                    <!-- Status -->
                    <section :class="cardCls">
                        <div class="flex items-center justify-between gap-4 p-5">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Active</p>
                                <p class="text-xs text-gray-400">Inactive stores are hidden from operations.</p>
                            </div>
                            <button type="button" @click="form.is_active = !form.is_active"
                                :class="form.is_active ? 'bg-primary-500' : 'bg-gray-300 dark:bg-[#3a3d4d]'"
                                class="relative inline-flex h-6 w-11 flex-shrink-0 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-[#1e2029]">
                                <span :class="form.is_active ? 'translate-x-6' : 'translate-x-1'" class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform" />
                            </button>
                        </div>
                    </section>

                    <!-- Branding / Logo upload -->
                    <section :class="cardCls">
                        <header :class="headerCls">
                            <span :class="iconBadge"><PhotoIcon class="h-5 w-5" /></span>
                            <h3 :class="titleCls">Logo</h3>
                        </header>
                        <div class="p-5">
                            <div @click="pickLogo"
                                @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false" @drop.prevent="onDrop"
                                class="flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed px-4 py-6 text-center transition-colors"
                                :class="dragging ? 'border-primary-500 bg-primary-500/5' : 'border-gray-300 hover:border-primary-500 hover:bg-gray-50 dark:border-[#3a3d4d] dark:hover:bg-white/5'">
                                <template v-if="logoIsUrl">
                                    <img :src="form.logo" alt="logo" class="mb-3 h-16 w-16 rounded-lg object-cover ring-1 ring-black/5" />
                                    <p class="text-sm font-medium text-primary-600 dark:text-primary-400">Change logo</p>
                                    <button type="button" @click.stop="form.logo = ''" class="mt-1 text-xs text-gray-400 hover:text-red-500">Remove</button>
                                </template>
                                <template v-else>
                                    <ArrowUpTrayIcon class="mb-2 h-7 w-7 text-gray-400" />
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Click or drag an image</p>
                                    <p class="mt-0.5 text-xs text-gray-400">PNG, JPG, WEBP — up to 5&nbsp;MB</p>
                                </template>
                                <p v-if="uploading" class="mt-2 flex items-center gap-1.5 text-xs text-primary-500"><ArrowPathIcon class="h-3.5 w-3.5 animate-spin" /> Uploading…</p>
                            </div>
                            <p v-if="uploadError" class="mt-2 text-xs text-red-500">{{ uploadError }}</p>
                            <input ref="logoInput" type="file" accept="image/*" class="hidden" @change="onLogoChange" />
                        </div>
                    </section>

                    <!-- Localization -->
                    <section :class="cardCls">
                        <header :class="headerCls">
                            <span :class="iconBadge"><GlobeAltIcon class="h-5 w-5" /></span>
                            <h3 :class="titleCls">Localization</h3>
                        </header>
                        <div class="space-y-4 p-5">
                            <Multiselect v-model="form.city_id" name="city_id" label="City" mode="single"
                                :options="$page.props.cities ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                            <Multiselect v-model="form.language_id" name="language_id" label="Language" mode="single"
                                :options="$page.props.languages ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                            <Multiselect v-model="form.currency_id" name="currency_id" label="Currency" mode="single"
                                :options="$page.props.currencies ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </PageContent>
</template>

<script setup lang="ts">
import { computed, ref } from "vue";
import axios from "axios";
import {
    BuildingStorefrontIcon,
    PhoneIcon,
    BuildingLibraryIcon,
    GlobeAltIcon,
    PhotoIcon,
    EnvelopeIcon,
    MapPinIcon,
    CameraIcon,
    ArrowPathIcon,
    ArrowUpTrayIcon,
} from "@heroicons/vue/24/outline";
import {
    Card,
    TextInput,
    PageContent,
    Checkbox,
    Multiselect,
} from "craftable-pro/Components";
import { InertiaForm } from "craftable-pro/types";
import type { StoreForm } from "./types";

interface Props {
    form: InertiaForm<StoreForm>;
    submit: void;
}
const props = defineProps<Props>();

const cardCls = "rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]";
const headerCls = "flex items-center gap-3 border-b border-gray-100 px-6 py-4 dark:border-[#2a2d38]";
const iconBadge = "flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-primary-500/10 text-primary-500";
const titleCls = "text-[15px] font-semibold text-gray-900 dark:text-white";
const subCls = "text-xs text-gray-400";

const initials = computed(() => {
    const src = (props.form.code || props.form.name || "S").toString().trim();
    return src.slice(0, 2).toUpperCase();
});

const logoIsUrl = computed(() => {
    const v = (props.form.logo || "").toString();
    return /^https?:\/\//i.test(v) || v.startsWith("/");
});

// ---- logo upload ----
const logoInput = ref<HTMLInputElement | null>(null);
const uploading = ref(false);
const dragging = ref(false);
const uploadError = ref("");

const pickLogo = () => logoInput.value?.click();

const onLogoChange = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) uploadLogo(file);
    (e.target as HTMLInputElement).value = "";
};

const onDrop = (e: DragEvent) => {
    dragging.value = false;
    const file = e.dataTransfer?.files?.[0];
    if (file) uploadLogo(file);
};

const uploadLogo = async (file: File) => {
    uploadError.value = "";
    if (!file.type.startsWith("image/")) { uploadError.value = "Please choose an image file."; return; }
    if (file.size > 5 * 1024 * 1024) { uploadError.value = "Image must be 5 MB or smaller."; return; }
    uploading.value = true;
    try {
        const data = new FormData();
        data.append("logo", file);
        const res = await axios.post(route("craftable-pro.stores.upload-logo"), data, {
            headers: { "Content-Type": "multipart/form-data" },
        });
        props.form.logo = res.data.url;
    } catch (err: any) {
        uploadError.value = err?.response?.data?.message ?? "Upload failed. Try again.";
    } finally {
        uploading.value = false;
    }
};
</script>
