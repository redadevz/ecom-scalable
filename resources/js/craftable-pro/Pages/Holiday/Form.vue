<template>
    <PageContent>
        <div class="mx-auto max-w-3xl 2xl:max-w-4xl">
            

            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1 space-y-4">
                    <Card>
                        <div class="space-y-4">
                            <Multiselect
                                v-model="form.store_id"
                                name="store_id"
                                :label="$t('craftable-pro', 'Store')"
                                mode="single"
                                :options="$page.props.stores ?? []"
                                options-value-prop="id"
                                options-label="name"
                                :searchable="true"
                            />
                            <TextInput
                                v-model="form.name"
                                name="name"
                                :label="$t('craftable-pro', 'Name')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.reason"
                                name="reason"
                                :label="$t('craftable-pro', 'Reason')"
                                type="text"
                            />
                            <DatePicker
                                v-model="form.starts_at"
                                name="starts_at"
                                mode="dateTime"
                                :label="$t('craftable-pro', 'Starts At')"
                                
                            />
                            <DatePicker
                                v-model="form.ends_at"
                                name="ends_at"
                                mode="dateTime"
                                :label="$t('craftable-pro', 'Ends At')"
                                
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
import type { HolidayForm } from "./types";



interface Props {
    form: InertiaForm<HolidayForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
