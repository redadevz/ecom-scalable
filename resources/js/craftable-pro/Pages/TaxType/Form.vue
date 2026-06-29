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
                </div>
            </Card>

            <Card title="Details">
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                    <TextInput
                        v-model="form.name"
                        name="name"
                        label="Name"
                        type="text"
                    />
                    <TextInput
                        v-model="form.code"
                        name="code"
                        label="Code"
                        type="text"
                    />
                    <div class="sm:col-span-2">
                        <TextInput
                            v-model="form.description"
                            name="description"
                            label="Description"
                            type="text"
                        />
                    </div>
                    <TextInput
                        v-model="form.value"
                        name="value"
                        label="Value"
                        type="number"
                    />
                    <DatePicker
                        v-model="form.start_time"
                        name="start_time"
                        mode="dateTime"
                        label="Start Time"
                    />
                    <DatePicker
                        v-model="form.end_time"
                        name="end_time"
                        mode="dateTime"
                        label="End Time"
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
                        v-model="form.is_percentage"
                        name="is_percentage"
                        label="Is Percentage"
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
import type { TaxTypeForm } from "./types";



interface Props {
    form: InertiaForm<TaxTypeForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
