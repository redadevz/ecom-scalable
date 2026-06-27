<template>
    <PageContent>
        <div class="mx-auto max-w-3xl 2xl:max-w-4xl">
            

            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1 space-y-4">
                    <Card>
                        <div class="space-y-4">
                            <Multiselect
                                v-model="form.city_id"
                                name="city_id"
                                :label="$t('craftable-pro', 'City')"
                                mode="single"
                                :options="$page.props.cities ?? []"
                                options-value-prop="id"
                                options-label="name"
                                :searchable="true"
                            />
                            <Multiselect
                                v-model="form.language_id"
                                name="language_id"
                                :label="$t('craftable-pro', 'Language')"
                                mode="single"
                                :options="$page.props.languages ?? []"
                                options-value-prop="id"
                                options-label="name"
                                :searchable="true"
                            />
                            <Multiselect
                                v-model="form.currency_id"
                                name="currency_id"
                                :label="$t('craftable-pro', 'Currency')"
                                mode="single"
                                :options="$page.props.currencies ?? []"
                                options-value-prop="id"
                                options-label="name"
                                :searchable="true"
                            />
                            <TextInput
                                v-model="form.code"
                                name="code"
                                :label="$t('craftable-pro', 'Code')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.name"
                                name="name"
                                :label="$t('craftable-pro', 'Name')"
                                type="text"
                            />
                            <Checkbox
                                v-model="form.is_active"
                                name="is_active"
                                :label="$t('craftable-pro', 'Is Active')"
                            />
                            <TextInput
                                v-model="form.legal_entity_name"
                                name="legal_entity_name"
                                :label="$t('craftable-pro', 'Legal Entity Name')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.tax_code"
                                name="tax_code"
                                :label="$t('craftable-pro', 'Tax Code')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.address"
                                name="address"
                                :label="$t('craftable-pro', 'Address')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.registration_number"
                                name="registration_number"
                                :label="$t('craftable-pro', 'Registration Number')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.phone"
                                name="phone"
                                :label="$t('craftable-pro', 'Phone')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.fax"
                                name="fax"
                                :label="$t('craftable-pro', 'Fax')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.email"
                                name="email"
                                :label="$t('craftable-pro', 'Email')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.logo"
                                name="logo"
                                :label="$t('craftable-pro', 'Logo')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.bank_branch"
                                name="bank_branch"
                                :label="$t('craftable-pro', 'Bank Branch')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.bank_code"
                                name="bank_code"
                                :label="$t('craftable-pro', 'Bank Code')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.bank_account"
                                name="bank_account"
                                :label="$t('craftable-pro', 'Bank Account')"
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
import type { StoreForm } from "./types";



interface Props {
    form: InertiaForm<StoreForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
