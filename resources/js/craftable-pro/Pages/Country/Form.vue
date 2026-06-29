<template>
    <PageContent>
        <div class="mx-auto max-w-5xl space-y-6">

            <Card title="Details">
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                    <TextInput
                        v-model="form.name"
                        name="name"
                        label="Name"
                        type="text"
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
import type { CountryForm } from "./types";



interface Props {
    form: InertiaForm<CountryForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
