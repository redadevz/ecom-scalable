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
                                v-model="form.created_at_store_id"
                                name="created_at_store_id"
                                :label="$t('craftable-pro', 'Created At Store')"
                                mode="single"
                                :options="$page.props.stores ?? []"
                                options-value-prop="id"
                                options-label="name"
                                :searchable="true"
                            />
                            <Multiselect
                                v-model="form.created_by"
                                name="created_by"
                                :label="$t('craftable-pro', 'Created By')"
                                mode="single"
                                :options="$page.props.craftable_pro_users ?? []"
                                options-value-prop="id"
                                options-label="email"
                                :searchable="true"
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
                            <Checkbox
                                v-model="form.is_registered_online"
                                name="is_registered_online"
                                :label="$t('craftable-pro', 'Is Registered Online')"
                            />
                            <TextInput
                                v-model="form.email"
                                name="email"
                                :label="$t('craftable-pro', 'Email')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.username"
                                name="username"
                                :label="$t('craftable-pro', 'Username')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.password"
                                name="password"
                                :label="$t('craftable-pro', 'Password')"
                                type="password"
                            />
                            <TextInput
                                v-model="form.credit"
                                name="credit"
                                :label="$t('craftable-pro', 'Credit')"
                                type="number"
                            />
                            <DatePicker
                                v-model="form.last_login_time"
                                name="last_login_time"
                                mode="dateTime"
                                :label="$t('craftable-pro', 'Last Login Time')"
                                
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
import type { CustomerForm } from "./types";



interface Props {
    form: InertiaForm<CustomerForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
