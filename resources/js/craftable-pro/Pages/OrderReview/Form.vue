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
                        v-model="form.customer_id"
                        name="customer_id"
                        label="Customer"
                        mode="single"
                        :options="$page.props.customers ?? []"
                        options-value-prop="id"
                        options-label="code"
                        :searchable="true"
                    />
                    <Multiselect
                        v-model="form.replied_by"
                        name="replied_by"
                        label="Replied By"
                        mode="single"
                        :options="$page.props.craftable_pro_users ?? []"
                        options-value-prop="id"
                        options-label="email"
                        :searchable="true"
                    />
                </div>
            </Card>

            <Card title="Details">
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                    <TextInput
                        v-model="form.review"
                        name="review"
                        label="Review"
                        type="text"
                    />
                    <DatePicker
                        v-model="form.review_time"
                        name="review_time"
                        mode="dateTime"
                        label="Review Time"
                    />
                    <TextInput
                        v-model="form.reply"
                        name="reply"
                        label="Reply"
                        type="text"
                    />
                    <DatePicker
                        v-model="form.reply_time"
                        name="reply_time"
                        mode="dateTime"
                        label="Reply Time"
                    />
                    <TextInput
                        v-model="form.compensation_value"
                        name="compensation_value"
                        label="Compensation Value"
                        type="text"
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
                        v-model="form.rating"
                        name="rating"
                        label="Rating"
                    />
                    <Checkbox
                        v-model="form.is_compensated"
                        name="is_compensated"
                        label="Is Compensated"
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
import type { OrderReviewForm } from "./types";



interface Props {
    form: InertiaForm<OrderReviewForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
