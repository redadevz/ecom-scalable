<template>
    <PageContent>
        <div class="mx-auto max-w-5xl space-y-6">

            <Card title="Relations">
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                    <Multiselect
                        v-model="form.store_id"
                        name="store_id"
                        label="Store"
                        mode="single"
                        :options="$page.props.stores ?? []"
                        options-value-prop="id"
                        options-label="name"
                        :searchable="true"
                    />
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
                        v-model="form.item_id"
                        name="item_id"
                        label="Item"
                        mode="single"
                        :options="$page.props.items ?? []"
                        options-value-prop="id"
                        options-label="name"
                        :searchable="true"
                    />
                </div>
            </Card>

            <Card title="Details">
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                    <TextInput
                        v-model="form.line_no"
                        name="line_no"
                        label="Line No"
                        type="text"
                    />
                    <div class="sm:col-span-2">
                        <TextInput
                            v-model="form.description"
                            name="description"
                            label="Description"
                            type="text"
                        />
                    </div>
                    <div class="sm:col-span-2">
                        <TextInput
                            v-model="form.customer_notes"
                            name="customer_notes"
                            label="Customer Notes"
                            type="text"
                        />
                    </div>
                    <TextInput
                        v-model="form.quantity"
                        name="quantity"
                        label="Quantity"
                        type="number"
                    />
                    <TextInput
                        v-model="form.current_item_cost"
                        name="current_item_cost"
                        label="Current Item Cost"
                        type="number"
                    />
                    <TextInput
                        v-model="form.markup_percentage"
                        name="markup_percentage"
                        label="Markup Percentage"
                        type="number"
                    />
                    <TextInput
                        v-model="form.price_before_tax"
                        name="price_before_tax"
                        label="Price Before Tax"
                        type="number"
                    />
                    <TextInput
                        v-model="form.tax_value"
                        name="tax_value"
                        label="Tax Value"
                        type="number"
                    />
                    <TextInput
                        v-model="form.price_after_tax"
                        name="price_after_tax"
                        label="Price After Tax"
                        type="number"
                    />
                    <TextInput
                        v-model="form.price_before_discount"
                        name="price_before_discount"
                        label="Price Before Discount"
                        type="number"
                    />
                    <TextInput
                        v-model="form.discount_value"
                        name="discount_value"
                        label="Discount Value"
                        type="number"
                    />
                    <TextInput
                        v-model="form.price_after_discount"
                        name="price_after_discount"
                        label="Price After Discount"
                        type="number"
                    />
                    <TextInput
                        v-model="form.price_adjustment"
                        name="price_adjustment"
                        label="Price Adjustment"
                        type="number"
                    />
                    <div class="sm:col-span-2">
                        <TextInput
                            v-model="form.price_adjustment_reason"
                            name="price_adjustment_reason"
                            label="Price Adjustment Reason"
                            type="text"
                        />
                    </div>
                    <TextInput
                        v-model="form.price"
                        name="price"
                        label="Price"
                        type="number"
                    />
                    <DatePicker
                        v-model="form.canceled_time"
                        name="canceled_time"
                        mode="dateTime"
                        label="Canceled Time"
                    />
                    <div class="sm:col-span-2">
                        <TextInput
                            v-model="form.cancel_reason"
                            name="cancel_reason"
                            label="Cancel Reason"
                            type="text"
                        />
                    </div>
                    <TextInput
                        v-model="form.return_quantity"
                        name="return_quantity"
                        label="Return Quantity"
                        type="number"
                    />
                    <DatePicker
                        v-model="form.return_time"
                        name="return_time"
                        mode="dateTime"
                        label="Return Time"
                    />
                    <TextInput
                        v-model="form.customer_review"
                        name="customer_review"
                        label="Customer Review"
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
                        v-model="form.is_canceled"
                        name="is_canceled"
                        label="Is Canceled"
                    />
                    <Checkbox
                        v-model="form.return_required"
                        name="return_required"
                        label="Return Required"
                    />
                    <Checkbox
                        v-model="form.customer_like"
                        name="customer_like"
                        label="Customer Like"
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
import type { OrderLineForm } from "./types";



interface Props {
    form: InertiaForm<OrderLineForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
