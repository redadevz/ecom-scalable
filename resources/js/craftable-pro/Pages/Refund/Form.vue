<template>
    <PageContent>
        <div class="mx-auto max-w-5xl space-y-6">

            <Card title="Relations">
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                    <Multiselect
                        v-model="form.sale_return_id"
                        name="sale_return_id"
                        label="Sale Return"
                        mode="single"
                        :options="$page.props.sale_returns ?? []"
                        options-value-prop="id"
                        options-label="description"
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
                </div>
            </Card>

            <Card title="Details">
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                    <TextInput
                        v-model="form.refund_no"
                        name="refund_no"
                        label="Refund No"
                        type="text"
                    />
                    <TextInput
                        v-model="form.amount"
                        name="amount"
                        label="Amount"
                        type="number"
                    />
                    <TextInput
                        v-model="form.cash_paid"
                        name="cash_paid"
                        label="Cash Paid"
                        type="number"
                    />
                    <TextInput
                        v-model="form.cash_change"
                        name="cash_change"
                        label="Cash Change"
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
                            v-model="form.comments"
                            name="comments"
                            label="Comments"
                            type="text"
                        />
                    </div>
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
import type { RefundForm } from "./types";



interface Props {
    form: InertiaForm<RefundForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
