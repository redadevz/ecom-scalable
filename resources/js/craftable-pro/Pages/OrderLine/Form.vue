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
                                v-model="form.item_id"
                                name="item_id"
                                :label="$t('craftable-pro', 'Item')"
                                mode="single"
                                :options="$page.props.items ?? []"
                                options-value-prop="id"
                                options-label="name"
                                :searchable="true"
                            />
                            <TextInput
                                v-model="form.line_no"
                                name="line_no"
                                :label="$t('craftable-pro', 'Line No')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.description"
                                name="description"
                                :label="$t('craftable-pro', 'Description')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.customer_notes"
                                name="customer_notes"
                                :label="$t('craftable-pro', 'Customer Notes')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.quantity"
                                name="quantity"
                                :label="$t('craftable-pro', 'Quantity')"
                                type="number"
                            />
                            <TextInput
                                v-model="form.current_item_cost"
                                name="current_item_cost"
                                :label="$t('craftable-pro', 'Current Item Cost')"
                                type="number"
                            />
                            <TextInput
                                v-model="form.markup_percentage"
                                name="markup_percentage"
                                :label="$t('craftable-pro', 'Markup Percentage')"
                                type="number"
                            />
                            <TextInput
                                v-model="form.price_before_tax"
                                name="price_before_tax"
                                :label="$t('craftable-pro', 'Price Before Tax')"
                                type="number"
                            />
                            <TextInput
                                v-model="form.tax_value"
                                name="tax_value"
                                :label="$t('craftable-pro', 'Tax Value')"
                                type="number"
                            />
                            <TextInput
                                v-model="form.price_after_tax"
                                name="price_after_tax"
                                :label="$t('craftable-pro', 'Price After Tax')"
                                type="number"
                            />
                            <TextInput
                                v-model="form.price_before_discount"
                                name="price_before_discount"
                                :label="$t('craftable-pro', 'Price Before Discount')"
                                type="number"
                            />
                            <TextInput
                                v-model="form.discount_value"
                                name="discount_value"
                                :label="$t('craftable-pro', 'Discount Value')"
                                type="number"
                            />
                            <TextInput
                                v-model="form.price_after_discount"
                                name="price_after_discount"
                                :label="$t('craftable-pro', 'Price After Discount')"
                                type="number"
                            />
                            <TextInput
                                v-model="form.price_adjustment"
                                name="price_adjustment"
                                :label="$t('craftable-pro', 'Price Adjustment')"
                                type="number"
                            />
                            <TextInput
                                v-model="form.price_adjustment_reason"
                                name="price_adjustment_reason"
                                :label="$t('craftable-pro', 'Price Adjustment Reason')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.price"
                                name="price"
                                :label="$t('craftable-pro', 'Price')"
                                type="number"
                            />
                            <Checkbox
                                v-model="form.is_canceled"
                                name="is_canceled"
                                :label="$t('craftable-pro', 'Is Canceled')"
                            />
                            <DatePicker
                                v-model="form.canceled_time"
                                name="canceled_time"
                                mode="dateTime"
                                :label="$t('craftable-pro', 'Canceled Time')"
                                
                            />
                            <TextInput
                                v-model="form.cancel_reason"
                                name="cancel_reason"
                                :label="$t('craftable-pro', 'Cancel Reason')"
                                type="text"
                            />
                            <Checkbox
                                v-model="form.return_required"
                                name="return_required"
                                :label="$t('craftable-pro', 'Return Required')"
                            />
                            <TextInput
                                v-model="form.return_quantity"
                                name="return_quantity"
                                :label="$t('craftable-pro', 'Return Quantity')"
                                type="number"
                            />
                            <DatePicker
                                v-model="form.return_time"
                                name="return_time"
                                mode="dateTime"
                                :label="$t('craftable-pro', 'Return Time')"
                                
                            />
                            <TextInput
                                v-model="form.customer_review"
                                name="customer_review"
                                :label="$t('craftable-pro', 'Customer Review')"
                                type="text"
                            />
                            <Checkbox
                                v-model="form.customer_like"
                                name="customer_like"
                                :label="$t('craftable-pro', 'Customer Like')"
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
import type { OrderLineForm } from "./types";



interface Props {
    form: InertiaForm<OrderLineForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
