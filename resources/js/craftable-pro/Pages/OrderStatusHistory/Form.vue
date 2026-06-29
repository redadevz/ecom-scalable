<template>
    <PageContent>
        <div class="mx-auto max-w-5xl space-y-6">

            <Card title="Relations">
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                    <Multiselect
                        v-model="form.order_id"
                        name="order_id"
                        label="Order"
                        mode="single"
                        :options="$page.props.order_headers ?? []"
                        options-value-prop="id"
                        options-label="order_no"
                        :searchable="true"
                    />
                    <Multiselect
                        v-model="form.order_status_id"
                        name="order_status_id"
                        label="Order Status"
                        mode="single"
                        :options="$page.props.order_statuses ?? []"
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
                        mode="dateTime"
                        label="Start Time"
                    />
                    <DatePicker
                        v-model="form.end_time"
                        name="end_time"
                        mode="dateTime"
                        label="End Time"
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
import type { OrderStatusHistoryForm } from "./types";



interface Props {
    form: InertiaForm<OrderStatusHistoryForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
