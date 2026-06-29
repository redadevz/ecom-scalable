<template>
    <PageContent>
        <div class="mx-auto max-w-5xl space-y-6">

            <Card title="Relations">
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                    <Multiselect
                        v-model="form.discount_type_id"
                        name="discount_type_id"
                        label="Discount Type"
                        mode="single"
                        :options="$page.props.discount_types ?? []"
                        options-value-prop="id"
                        options-label="name"
                        :searchable="true"
                    />
                </div>
            </Card>

            <Card title="Details">
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                    <DatePicker
                        v-model="form.start_time"
                        name="start_time"
                        mode="time"
                        label="Start Time"
                        hide-time-header
                    />
                    <DatePicker
                        v-model="form.end_time"
                        name="end_time"
                        mode="time"
                        label="End Time"
                        hide-time-header
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
                        v-model="form.day_of_week"
                        name="day_of_week"
                        label="Day Of Week"
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
import type { DiscountScheduleForm } from "./types";



interface Props {
    form: InertiaForm<DiscountScheduleForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
