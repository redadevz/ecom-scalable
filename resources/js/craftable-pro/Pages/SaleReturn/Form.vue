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
                </div>
            </Card>

            <Card title="Details">
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                    <DatePicker
                        v-model="form.entry_stock_time"
                        name="entry_stock_time"
                        mode="dateTime"
                        label="Entry Stock Time"
                    />
                    <TextInput
                        v-model="form.refund_amount"
                        name="refund_amount"
                        label="Refund Amount"
                        type="number"
                    />
                    <DatePicker
                        v-model="form.refund_time"
                        name="refund_time"
                        mode="dateTime"
                        label="Refund Time"
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
                        v-model="form.is_refund_required"
                        name="is_refund_required"
                        label="Is Refund Required"
                    />
                    <Checkbox
                        v-model="form.is_refunded"
                        name="is_refunded"
                        label="Is Refunded"
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
import type { SaleReturnForm } from "./types";



interface Props {
    form: InertiaForm<SaleReturnForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
