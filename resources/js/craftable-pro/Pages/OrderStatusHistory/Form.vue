<template>
    <PageContent>
        <div class="mx-auto max-w-3xl 2xl:max-w-4xl">
            

            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1 space-y-4">
                    <Card>
                        <div class="space-y-4">
                            <Multiselect
                                v-model="form.order_id"
                                name="order_id"
                                :label="$t('craftable-pro', 'Order')"
                                mode="single"
                                :options="$page.props.order_headers ?? []"
                                options-value-prop="id"
                                options-label="order_no"
                                :searchable="true"
                            />
                            <Multiselect
                                v-model="form.order_status_id"
                                name="order_status_id"
                                :label="$t('craftable-pro', 'Order Status')"
                                mode="single"
                                :options="$page.props.order_statuses ?? []"
                                options-value-prop="id"
                                options-label="name"
                                :searchable="true"
                            />
                            <DatePicker
                                v-model="form.start_time"
                                name="start_time"
                                mode="dateTime"
                                :label="$t('craftable-pro', 'Start Time')"
                                
                            />
                            <DatePicker
                                v-model="form.end_time"
                                name="end_time"
                                mode="dateTime"
                                :label="$t('craftable-pro', 'End Time')"
                                
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
import type { OrderStatusHistoryForm } from "./types";



interface Props {
    form: InertiaForm<OrderStatusHistoryForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
