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
                        v-model="form.sale_channel_id"
                        name="sale_channel_id"
                        label="Sale Channel"
                        mode="single"
                        :options="$page.props.sale_channels ?? []"
                        options-value-prop="id"
                        options-label="name"
                        :searchable="true"
                    />
                    <Multiselect
                        v-model="form.delivery_type_id"
                        name="delivery_type_id"
                        label="Delivery Type"
                        mode="single"
                        :options="$page.props.delivery_types ?? []"
                        options-value-prop="id"
                        options-label="name"
                        :searchable="true"
                    />
                    <Multiselect
                        v-model="form.payment_method_id"
                        name="payment_method_id"
                        label="Payment Method"
                        mode="single"
                        :options="$page.props.payment_methods ?? []"
                        options-value-prop="id"
                        options-label="name"
                        :searchable="true"
                    />
                    <Multiselect
                        v-model="form.payment_time_id"
                        name="payment_time_id"
                        label="Payment Time"
                        mode="single"
                        :options="$page.props.payment_times ?? []"
                        options-value-prop="id"
                        options-label="name"
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
                        v-model="form.loyalty_card_id"
                        name="loyalty_card_id"
                        label="Loyalty Card"
                        mode="single"
                        :options="$page.props.loyalty_cards ?? []"
                        options-value-prop="id"
                        options-label="code"
                        :searchable="true"
                    />
                    <Multiselect
                        v-model="form.created_by"
                        name="created_by"
                        label="Created By"
                        mode="single"
                        :options="$page.props.craftable_pro_users ?? []"
                        options-value-prop="id"
                        options-label="email"
                        :searchable="true"
                    />
                    <Multiselect
                        v-model="form.approved_by"
                        name="approved_by"
                        label="Approved By"
                        mode="single"
                        :options="$page.props.craftable_pro_users ?? []"
                        options-value-prop="id"
                        options-label="email"
                        :searchable="true"
                    />
                    <Multiselect
                        v-model="form.managed_by"
                        name="managed_by"
                        label="Managed By"
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
                        v-model="form.order_no"
                        name="order_no"
                        label="Order No"
                        type="text"
                    />
                    <div class="sm:col-span-2">
                        <TextInput
                            v-model="form.customer_notes"
                            name="customer_notes"
                            label="Customer Notes"
                            type="text"
                        />
                    </div>
                    <TextInput
                        v-model="form.price_before_tax"
                        name="price_before_tax"
                        label="Price Before Tax"
                        type="number"
                    />
                    <TextInput
                        v-model="form.total_tax_value"
                        name="total_tax_value"
                        label="Total Tax Value"
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
                        v-model="form.order_items_discount"
                        name="order_items_discount"
                        label="Order Items Discount"
                        type="number"
                    />
                    <TextInput
                        v-model="form.order_discount"
                        name="order_discount"
                        label="Order Discount"
                        type="number"
                    />
                    <TextInput
                        v-model="form.total_discount_value"
                        name="total_discount_value"
                        label="Total Discount Value"
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
                    <TextInput
                        v-model="form.latest_status"
                        name="latest_status"
                        label="Latest Status"
                        type="text"
                    />
                    <DatePicker
                        v-model="form.latest_status_update"
                        name="latest_status_update"
                        mode="dateTime"
                        label="Latest Status Update"
                    />
                    <DatePicker
                        v-model="form.submitted_time"
                        name="submitted_time"
                        mode="dateTime"
                        label="Submitted Time"
                    />
                    <DatePicker
                        v-model="form.approved_time"
                        name="approved_time"
                        mode="dateTime"
                        label="Approved Time"
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
                    <DatePicker
                        v-model="form.scheduled_time"
                        name="scheduled_time"
                        mode="dateTime"
                        label="Scheduled Time"
                    />
                    <DatePicker
                        v-model="form.ready_time"
                        name="ready_time"
                        mode="dateTime"
                        label="Ready Time"
                    />
                    <DatePicker
                        v-model="form.delivered_time"
                        name="delivered_time"
                        mode="dateTime"
                        label="Delivered Time"
                    />
                    <DatePicker
                        v-model="form.payment_time"
                        name="payment_time"
                        mode="dateTime"
                        label="Payment Time"
                    />
                    <DatePicker
                        v-model="form.completed_time"
                        name="completed_time"
                        mode="dateTime"
                        label="Completed Time"
                    />
                    <DatePicker
                        v-model="form.return_time"
                        name="return_time"
                        mode="dateTime"
                        label="Return Time"
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
                        v-model="form.is_submitted"
                        name="is_submitted"
                        label="Is Submitted"
                    />
                    <Checkbox
                        v-model="form.is_approved"
                        name="is_approved"
                        label="Is Approved"
                    />
                    <Checkbox
                        v-model="form.is_canceled"
                        name="is_canceled"
                        label="Is Canceled"
                    />
                    <Checkbox
                        v-model="form.is_scheduled"
                        name="is_scheduled"
                        label="Is Scheduled"
                    />
                    <Checkbox
                        v-model="form.is_ready"
                        name="is_ready"
                        label="Is Ready"
                    />
                    <Checkbox
                        v-model="form.is_delivered"
                        name="is_delivered"
                        label="Is Delivered"
                    />
                    <Checkbox
                        v-model="form.is_paid"
                        name="is_paid"
                        label="Is Paid"
                    />
                    <Checkbox
                        v-model="form.is_completed"
                        name="is_completed"
                        label="Is Completed"
                    />
                    <Checkbox
                        v-model="form.return_required"
                        name="return_required"
                        label="Return Required"
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
import type { OrderHeaderForm } from "./types";



interface Props {
    form: InertiaForm<OrderHeaderForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
