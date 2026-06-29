<template>
    <PageContent>
        <div class="mx-auto max-w-5xl space-y-6">

            <Card title="Relations">
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                    <Multiselect
                        v-model="form.store_id"
                        name="store_id"
                        label="Store"
                        mode="single"
                        :options="$page.props.stores ?? []"
                        options-value-prop="id"
                        options-label="name"
                        :searchable="true"
                    />
                    <Multiselect
                        v-model="form.city_id"
                        name="city_id"
                        label="City"
                        mode="single"
                        :options="$page.props.cities ?? []"
                        options-value-prop="id"
                        options-label="name"
                        :searchable="true"
                    />
                    <Multiselect
                        v-model="form.created_by"
                        name="created_by"
                        label="Created By"
                        mode="single"
                        :options="$page.props.craftable_pro_users ?? []"
                        options-value-prop="id"
                        options-label="email"
                        :searchable="true"
                    />
                </div>
            </Card>

            <Card title="Details">
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                    <TextInput
                        v-model="form.code"
                        name="code"
                        label="Code"
                        type="text"
                    />
                    <TextInput
                        v-model="form.phone"
                        name="phone"
                        label="Phone"
                        type="text"
                    />
                    <TextInput
                        v-model="form.first_name"
                        name="first_name"
                        label="First Name"
                        type="text"
                    />
                    <TextInput
                        v-model="form.last_name"
                        name="last_name"
                        label="Last Name"
                        type="text"
                    />
                    <TextInput
                        v-model="form.company_name"
                        name="company_name"
                        label="Company Name"
                        type="text"
                    />
                    <TextInput
                        v-model="form.tax_number"
                        name="tax_number"
                        label="Tax Number"
                        type="text"
                    />
                    <TextInput
                        v-model="form.billing_address"
                        name="billing_address"
                        label="Billing Address"
                        type="text"
                    />
                    <TextInput
                        v-model="form.postal_code"
                        name="postal_code"
                        label="Postal Code"
                        type="text"
                    />
                    <TextInput
                        v-model="form.email"
                        name="email"
                        label="Email"
                        type="text"
                    />
                    <div class="sm:col-span-2">
                        <TextInput
                            v-model="form.comments"
                            name="comments"
                            label="Comments"
                            type="text"
                        />
                    </div>
                </div>
            </Card>

            <Card title="Options">
                <div class="flex flex-wrap gap-x-10 gap-y-4">
                    <Checkbox
                        v-model="form.is_company"
                        name="is_company"
                        label="Is Company"
                    />
                    <Checkbox
                        v-model="form.is_tax_exempted"
                        name="is_tax_exempted"
                        label="Is Tax Exempted"
                    />
                    <Checkbox
                        v-model="form.is_active"
                        name="is_active"
                        label="Is Active"
                    />
                </div>
            </Card>

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
