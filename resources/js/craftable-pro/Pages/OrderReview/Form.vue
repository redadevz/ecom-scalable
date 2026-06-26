<template>
    <PageContent>
        <div class="mx-auto max-w-3xl 2xl:max-w-4xl">
            

            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1 space-y-4">
                    <Card>
                        <div class="space-y-4">
                            <TextInput
                                v-model="form.order_id"
                                name="order_id"
                                :label="$t('craftable-pro', 'Order Id')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.customer_id"
                                name="customer_id"
                                :label="$t('craftable-pro', 'Customer Id')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.replied_by"
                                name="replied_by"
                                :label="$t('craftable-pro', 'Replied By')"
                                type="text"
                            />
                            <Checkbox
                                v-model="form.rating"
                                name="rating"
                                :label="$t('craftable-pro', 'Rating')"
                            />
                            <TextInput
                                v-model="form.review"
                                name="review"
                                :label="$t('craftable-pro', 'Review')"
                                type="text"
                            />
                            <DatePicker
                                v-model="form.review_time"
                                name="review_time"
                                mode="dateTime"
                                :label="$t('craftable-pro', 'Review Time')"
                                
                            />
                            <TextInput
                                v-model="form.reply"
                                name="reply"
                                :label="$t('craftable-pro', 'Reply')"
                                type="text"
                            />
                            <DatePicker
                                v-model="form.reply_time"
                                name="reply_time"
                                mode="dateTime"
                                :label="$t('craftable-pro', 'Reply Time')"
                                
                            />
                            <Checkbox
                                v-model="form.is_compensated"
                                name="is_compensated"
                                :label="$t('craftable-pro', 'Is Compensated')"
                            />
                            <TextInput
                                v-model="form.compensation_value"
                                name="compensation_value"
                                :label="$t('craftable-pro', 'Compensation Value')"
                                type="text"
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
import type { OrderReviewForm } from "./types";



interface Props {
    form: InertiaForm<OrderReviewForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
