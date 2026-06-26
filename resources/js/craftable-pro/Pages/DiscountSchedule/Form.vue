<template>
    <PageContent>
        <div class="mx-auto max-w-3xl 2xl:max-w-4xl">
            

            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1 space-y-4">
                    <Card>
                        <div class="space-y-4">
                            <TextInput
                                v-model="form.discount_type_id"
                                name="discount_type_id"
                                :label="$t('craftable-pro', 'Discount Type Id')"
                                type="text"
                            />
                            <Checkbox
                                v-model="form.day_of_week"
                                name="day_of_week"
                                :label="$t('craftable-pro', 'Day Of Week')"
                            />
                            <DatePicker
                                v-model="form.start_time"
                                name="start_time"
                                mode="time"
                                :label="$t('craftable-pro', 'Start Time')"
                                hide-time-header
                            />
                            <DatePicker
                                v-model="form.end_time"
                                name="end_time"
                                mode="time"
                                :label="$t('craftable-pro', 'End Time')"
                                hide-time-header
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
import type { DiscountScheduleForm } from "./types";



interface Props {
    form: InertiaForm<DiscountScheduleForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
