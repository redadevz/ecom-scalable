<template>
    <PageContent>
        <div class="mx-auto max-w-3xl 2xl:max-w-4xl">
            

            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1 space-y-4">
                    <Card>
                        <div class="space-y-4">
                            <TextInput
                                v-model="form.store_id"
                                name="store_id"
                                :label="$t('craftable-pro', 'Store Id')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.loyalty_card_type_id"
                                name="loyalty_card_type_id"
                                :label="$t('craftable-pro', 'Loyalty Card Type Id')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.name"
                                name="name"
                                :label="$t('craftable-pro', 'Name')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.description"
                                name="description"
                                :label="$t('craftable-pro', 'Description')"
                                type="text"
                            />
                            <Checkbox
                                v-model="form.is_percentage"
                                name="is_percentage"
                                :label="$t('craftable-pro', 'Is Percentage')"
                            />
                            <TextInput
                                v-model="form.value"
                                name="value"
                                :label="$t('craftable-pro', 'Value')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.coupon_code"
                                name="coupon_code"
                                :label="$t('craftable-pro', 'Coupon Code')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.min_order_value"
                                name="min_order_value"
                                :label="$t('craftable-pro', 'Min Order Value')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.min_item_quantity"
                                name="min_item_quantity"
                                :label="$t('craftable-pro', 'Min Item Quantity')"
                                type="text"
                            />
                            <Checkbox
                                v-model="form.apply_to_all"
                                name="apply_to_all"
                                :label="$t('craftable-pro', 'Apply To All')"
                            />
                            <Checkbox
                                v-model="form.apply_to_next"
                                name="apply_to_next"
                                :label="$t('craftable-pro', 'Apply To Next')"
                            />
                            <TextInput
                                v-model="form.max_discount_value"
                                name="max_discount_value"
                                :label="$t('craftable-pro', 'Max Discount Value')"
                                type="text"
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
                            <Checkbox
                                v-model="form.is_active"
                                name="is_active"
                                :label="$t('craftable-pro', 'Is Active')"
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
import type { DiscountTypeForm } from "./types";



interface Props {
    form: InertiaForm<DiscountTypeForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
