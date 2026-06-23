<template>
    <PageContent>
        <div class="mx-auto max-w-3xl 2xl:max-w-4xl">
            

            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1 space-y-4">
                    <Card>
                        <div class="space-y-4">
                            <TextInput
                                v-model="form.store_id"
                                name="store_id"
                                :label="$t('craftable-pro', 'Store Id')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.city_id"
                                name="city_id"
                                :label="$t('craftable-pro', 'City Id')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.created_by"
                                name="created_by"
                                :label="$t('craftable-pro', 'Created By')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.code"
                                name="code"
                                :label="$t('craftable-pro', 'Code')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.phone"
                                name="phone"
                                :label="$t('craftable-pro', 'Phone')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.first_name"
                                name="first_name"
                                :label="$t('craftable-pro', 'First Name')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.last_name"
                                name="last_name"
                                :label="$t('craftable-pro', 'Last Name')"
                                type="text"
                            />
                            <Checkbox
                                v-model="form.is_company"
                                name="is_company"
                                :label="$t('craftable-pro', 'Is Company')"
                            />
                            <TextInput
                                v-model="form.company_name"
                                name="company_name"
                                :label="$t('craftable-pro', 'Company Name')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.tax_number"
                                name="tax_number"
                                :label="$t('craftable-pro', 'Tax Number')"
                                type="text"
                            />
                            <Checkbox
                                v-model="form.is_tax_exempted"
                                name="is_tax_exempted"
                                :label="$t('craftable-pro', 'Is Tax Exempted')"
                            />
                            <TextInput
                                v-model="form.billing_address"
                                name="billing_address"
                                :label="$t('craftable-pro', 'Billing Address')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.postal_code"
                                name="postal_code"
                                :label="$t('craftable-pro', 'Postal Code')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.email"
                                name="email"
                                :label="$t('craftable-pro', 'Email')"
                                type="text"
                            />
                            <Checkbox
                                v-model="form.is_active"
                                name="is_active"
                                :label="$t('craftable-pro', 'Is Active')"
                            />
                            <TextInput
                                v-model="form.comments"
                                name="comments"
                                :label="$t('craftable-pro', 'Comments')"
                                type="text"
                            />
                        </div>
                    </Card>
                </div>
            </div>
        </div>
    </PageContent>
</template>

<script setup lang="ts">
import { ref } from "vue"
import {
    Card,
    TextInput,
    Wysiwyg,
    TextArea,
    PageContent,
    DatePicker,
    Checkbox,
    Dropzone,
    ImageUpload,
    Multiselect,
    CardLocaleSwitcher
} from "craftable-pro/Components";
import { InertiaForm } from "craftable-pro/types";
import type { SupplierForm } from "./types";



interface Props {
    form: InertiaForm<SupplierForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
