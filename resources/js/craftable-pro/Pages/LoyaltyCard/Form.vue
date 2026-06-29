<template>
    <PageContent>
        <div class="mx-auto max-w-5xl space-y-6">

            <Card title="Relations">
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                    <Multiselect
                        v-model="form.loyalty_card_type_id"
                        name="loyalty_card_type_id"
                        label="Loyalty Card Type"
                        mode="single"
                        :options="$page.props.loyalty_card_types ?? []"
                        options-value-prop="id"
                        options-label="name"
                        :searchable="true"
                    />
                    <Multiselect
                        v-model="form.customer_id"
                        name="customer_id"
                        label="Customer"
                        mode="single"
                        :options="$page.props.customers ?? []"
                        options-value-prop="id"
                        options-label="code"
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
import type { LoyaltyCardForm } from "./types";



interface Props {
    form: InertiaForm<LoyaltyCardForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
