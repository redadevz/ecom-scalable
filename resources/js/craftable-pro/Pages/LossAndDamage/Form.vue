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
                    <DatePicker
                        v-model="form.exit_stock_time"
                        name="exit_stock_time"
                        mode="dateTime"
                        label="Exit Stock Time"
                    />
                    <div class="sm:col-span-2">
                        <TextInput
                            v-model="form.description"
                            name="description"
                            label="Description"
                            type="text"
                        />
                    </div>
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
import type { LossAndDamageForm } from "./types";



interface Props {
    form: InertiaForm<LossAndDamageForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
