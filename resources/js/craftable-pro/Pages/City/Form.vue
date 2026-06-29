<template>
    <PageContent>
        <div class="mx-auto max-w-5xl space-y-6">

            <Card title="Relations">
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                    <Multiselect
                        v-model="form.region_id"
                        name="region_id"
                        label="Region"
                        mode="single"
                        :options="$page.props.regions ?? []"
                        options-value-prop="id"
                        options-label="name"
                        :searchable="true"
                    />
                    <Multiselect
                        v-model="form.timezone_id"
                        name="timezone_id"
                        label="Timezone"
                        mode="single"
                        :options="$page.props.time_zones ?? []"
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
                        v-model="form.zipcode"
                        name="zipcode"
                        label="Zipcode"
                        type="number"
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
import type { CityForm } from "./types";



interface Props {
    form: InertiaForm<CityForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
