<template>
    <PageContent>
        <div class="mx-auto max-w-3xl 2xl:max-w-4xl">
            

            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1 space-y-4">
                    <Card>
                        <div class="space-y-4">
                            <Multiselect
                                v-model="form.invoice_id"
                                name="invoice_id"
                                :label="$t('craftable-pro', 'Invoice')"
                                mode="single"
                                :options="$page.props.invoices ?? []"
                                options-value-prop="id"
                                options-label="invoice_no"
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
                            <TextInput
                                v-model="form.payment_no"
                                name="payment_no"
                                :label="$t('craftable-pro', 'Payment No')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.amount"
                                name="amount"
                                :label="$t('craftable-pro', 'Amount')"
                                type="number"
                            />
                            <TextInput
                                v-model="form.cash_paid"
                                name="cash_paid"
                                :label="$t('craftable-pro', 'Cash Paid')"
                                type="number"
                            />
                            <TextInput
                                v-model="form.cash_change"
                                name="cash_change"
                                :label="$t('craftable-pro', 'Cash Change')"
                                type="number"
                            />
                            <DatePicker
                                v-model="form.payment_time"
                                name="payment_time"
                                mode="dateTime"
                                :label="$t('craftable-pro', 'Payment Time')"
                                
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
import type { PaymentForm } from "./types";



interface Props {
    form: InertiaForm<PaymentForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
