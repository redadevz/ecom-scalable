<template>
    <PageContent>
        <div class="mx-auto max-w-5xl space-y-6">

            <Card title="Relations">
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
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
                        v-model="form.language_id"
                        name="language_id"
                        label="Language"
                        mode="single"
                        :options="$page.props.languages ?? []"
                        options-value-prop="id"
                        options-label="name"
                        :searchable="true"
                    />
                    <Multiselect
                        v-model="form.currency_id"
                        name="currency_id"
                        label="Currency"
                        mode="single"
                        :options="$page.props.currencies ?? []"
                        options-value-prop="id"
                        options-label="name"
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
                        v-model="form.name"
                        name="name"
                        label="Name"
                        type="text"
                    />
                    <TextInput
                        v-model="form.legal_entity_name"
                        name="legal_entity_name"
                        label="Legal Entity Name"
                        type="text"
                    />
                    <TextInput
                        v-model="form.tax_code"
                        name="tax_code"
                        label="Tax Code"
                        type="text"
                    />
                    <div class="sm:col-span-2">
                        <TextInput
                            v-model="form.address"
                            name="address"
                            label="Address"
                            type="text"
                        />
                    </div>
                    <TextInput
                        v-model="form.registration_number"
                        name="registration_number"
                        label="Registration Number"
                        type="text"
                    />
                    <TextInput
                        v-model="form.phone"
                        name="phone"
                        label="Phone"
                        type="text"
                    />
                    <TextInput
                        v-model="form.fax"
                        name="fax"
                        label="Fax"
                        type="text"
                    />
                    <TextInput
                        v-model="form.email"
                        name="email"
                        label="Email"
                        type="text"
                    />
                    <TextInput
                        v-model="form.logo"
                        name="logo"
                        label="Logo"
                        type="text"
                    />
                    <TextInput
                        v-model="form.bank_branch"
                        name="bank_branch"
                        label="Bank Branch"
                        type="text"
                    />
                    <TextInput
                        v-model="form.bank_code"
                        name="bank_code"
                        label="Bank Code"
                        type="text"
                    />
                    <TextInput
                        v-model="form.bank_account"
                        name="bank_account"
                        label="Bank Account"
                        type="text"
                    />
                </div>
            </Card>

            <Card title="Options">
                <div class="flex flex-wrap gap-x-10 gap-y-4">
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
import type { StoreForm } from "./types";



interface Props {
    form: InertiaForm<StoreForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
