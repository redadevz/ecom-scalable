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
                                v-model="form.sale_channel_id"
                                name="sale_channel_id"
                                :label="$t('craftable-pro', 'Sale Channel')"
                                mode="single"
                                :options="$page.props.sale_channels ?? []"
                                options-value-prop="id"
                                options-label="name"
                                :searchable="true"
                            />
                            <Multiselect
                                v-model="form.delivery_type_id"
                                name="delivery_type_id"
                                :label="$t('craftable-pro', 'Delivery Type')"
                                mode="single"
                                :options="$page.props.delivery_types ?? []"
                                options-value-prop="id"
                                options-label="name"
                                :searchable="true"
                            />
                            <Multiselect
                                v-model="form.payment_method_id"
                                name="payment_method_id"
                                :label="$t('craftable-pro', 'Payment Method')"
                                mode="single"
                                :options="$page.props.payment_methods ?? []"
                                options-value-prop="id"
                                options-label="name"
                                :searchable="true"
                            />
                            <Multiselect
                                v-model="form.payment_time_id"
                                name="payment_time_id"
                                :label="$t('craftable-pro', 'Payment Time')"
                                mode="single"
                                :options="$page.props.payment_times ?? []"
                                options-value-prop="id"
                                options-label="name"
                                :searchable="true"
                            />
                            <Multiselect
                                v-model="form.customer_id"
                                name="customer_id"
                                :label="$t('craftable-pro', 'Customer')"
                                mode="single"
                                :options="$page.props.customers ?? []"
                                options-value-prop="id"
                                options-label="code"
                                :searchable="true"
                            />
                            <Multiselect
                                v-model="form.loyalty_card_id"
                                name="loyalty_card_id"
                                :label="$t('craftable-pro', 'Loyalty Card')"
                                mode="single"
                                :options="$page.props.loyalty_cards ?? []"
                                options-value-prop="id"
                                options-label="code"
                                :searchable="true"
                            />
                            <Multiselect
                                v-model="form.created_by"
                                name="created_by"
                                :label="$t('craftable-pro', 'Created By')"
                                mode="single"
                                :options="$page.props.craftable_pro_users ?? []"
                                options-value-prop="id"
                                options-label="email"
                                :searchable="true"
                            />
                            <Multiselect
                                v-model="form.approved_by"
                                name="approved_by"
                                :label="$t('craftable-pro', 'Approved By')"
                                mode="single"
                                :options="$page.props.craftable_pro_users ?? []"
                                options-value-prop="id"
                                options-label="email"
                                :searchable="true"
                            />
                            <Multiselect
                                v-model="form.managed_by"
                                name="managed_by"
                                :label="$t('craftable-pro', 'Managed By')"
                                mode="single"
                                :options="$page.props.craftable_pro_users ?? []"
                                options-value-prop="id"
                                options-label="email"
                                :searchable="true"
                            />
                            <TextInput
                                v-model="form.order_no"
                                name="order_no"
                                :label="$t('craftable-pro', 'Order No')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.customer_notes"
                                name="customer_notes"
                                :label="$t('craftable-pro', 'Customer Notes')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.price_before_tax"
                                name="price_before_tax"
                                :label="$t('craftable-pro', 'Price Before Tax')"
                                type="number"
                            />
                            <TextInput
                                v-model="form.total_tax_value"
                                name="total_tax_value"
                                :label="$t('craftable-pro', 'Total Tax Value')"
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
                                v-model="form.order_items_discount"
                                name="order_items_discount"
                                :label="$t('craftable-pro', 'Order Items Discount')"
                                type="number"
                            />
                            <TextInput
                                v-model="form.order_discount"
                                name="order_discount"
                                :label="$t('craftable-pro', 'Order Discount')"
                                type="number"
                            />
                            <TextInput
                                v-model="form.total_discount_value"
                                name="total_discount_value"
                                :label="$t('craftable-pro', 'Total Discount Value')"
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
                            <TextInput
                                v-model="form.latest_status"
                                name="latest_status"
                                :label="$t('craftable-pro', 'Latest Status')"
                                type="text"
                            />
                            <DatePicker
                                v-model="form.latest_status_update"
                                name="latest_status_update"
                                mode="dateTime"
                                :label="$t('craftable-pro', 'Latest Status Update')"
                                
                            />
                            <Checkbox
                                v-model="form.is_submitted"
                                name="is_submitted"
                                :label="$t('craftable-pro', 'Is Submitted')"
                            />
                            <DatePicker
                                v-model="form.submitted_time"
                                name="submitted_time"
                                mode="dateTime"
                                :label="$t('craftable-pro', 'Submitted Time')"
                                
                            />
                            <Checkbox
                                v-model="form.is_approved"
                                name="is_approved"
                                :label="$t('craftable-pro', 'Is Approved')"
                            />
                            <DatePicker
                                v-model="form.approved_time"
                                name="approved_time"
                                mode="dateTime"
                                :label="$t('craftable-pro', 'Approved Time')"
                                
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
                                v-model="form.is_scheduled"
                                name="is_scheduled"
                                :label="$t('craftable-pro', 'Is Scheduled')"
                            />
                            <DatePicker
                                v-model="form.scheduled_time"
                                name="scheduled_time"
                                mode="dateTime"
                                :label="$t('craftable-pro', 'Scheduled Time')"
                                
                            />
                            <Checkbox
                                v-model="form.is_ready"
                                name="is_ready"
                                :label="$t('craftable-pro', 'Is Ready')"
                            />
                            <DatePicker
                                v-model="form.ready_time"
                                name="ready_time"
                                mode="dateTime"
                                :label="$t('craftable-pro', 'Ready Time')"
                                
                            />
                            <Checkbox
                                v-model="form.is_delivered"
                                name="is_delivered"
                                :label="$t('craftable-pro', 'Is Delivered')"
                            />
                            <DatePicker
                                v-model="form.delivered_time"
                                name="delivered_time"
                                mode="dateTime"
                                :label="$t('craftable-pro', 'Delivered Time')"
                                
                            />
                            <Checkbox
                                v-model="form.is_paid"
                                name="is_paid"
                                :label="$t('craftable-pro', 'Is Paid')"
                            />
                            <DatePicker
                                v-model="form.payment_time"
                                name="payment_time"
                                mode="dateTime"
                                :label="$t('craftable-pro', 'Payment Time')"
                                
                            />
                            <Checkbox
                                v-model="form.is_completed"
                                name="is_completed"
                                :label="$t('craftable-pro', 'Is Completed')"
                            />
                            <DatePicker
                                v-model="form.completed_time"
                                name="completed_time"
                                mode="dateTime"
                                :label="$t('craftable-pro', 'Completed Time')"
                                
                            />
                            <Checkbox
                                v-model="form.return_required"
                                name="return_required"
                                :label="$t('craftable-pro', 'Return Required')"
                            />
                            <DatePicker
                                v-model="form.return_time"
                                name="return_time"
                                mode="dateTime"
                                :label="$t('craftable-pro', 'Return Time')"
                                
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
import type { OrderHeaderForm } from "./types";



interface Props {
    form: InertiaForm<OrderHeaderForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
