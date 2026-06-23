<template>
    <PageContent>
        <div class="mx-auto max-w-3xl 2xl:max-w-4xl">
            

            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1 space-y-4">
                    <Card>
                        <div class="space-y-4">
                            <TextInput
                                v-model="form.name"
                                name="name"
                                :label="$t('craftable-pro', 'Name')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.region_id"
                                name="region_id"
                                :label="$t('craftable-pro', 'Region Id')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.timezone_id"
                                name="timezone_id"
                                :label="$t('craftable-pro', 'Timezone Id')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.zipcode"
                                name="zipcode"
                                :label="$t('craftable-pro', 'Zipcode')"
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
import type { CityForm } from "./types";



interface Props {
    form: InertiaForm<CityForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
