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
                            <DatePicker
                                v-model="form.entry_stock_time"
                                name="entry_stock_time"
                                mode="dateTime"
                                :label="$t('craftable-pro', 'Entry Stock Time')"
                                
                            />
                            <Checkbox
                                v-model="form.is_refund_required"
                                name="is_refund_required"
                                :label="$t('craftable-pro', 'Is Refund Required')"
                            />
                            <TextInput
                                v-model="form.refund_amount"
                                name="refund_amount"
                                :label="$t('craftable-pro', 'Refund Amount')"
                                type="number"
                            />
                            <Checkbox
                                v-model="form.is_refunded"
                                name="is_refunded"
                                :label="$t('craftable-pro', 'Is Refunded')"
                            />
                            <DatePicker
                                v-model="form.refund_time"
                                name="refund_time"
                                mode="dateTime"
                                :label="$t('craftable-pro', 'Refund Time')"
                                
                            />
                            <TextInput
                                v-model="form.description"
                                name="description"
                                :label="$t('craftable-pro', 'Description')"
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
import type { SaleReturnForm } from "./types";



interface Props {
    form: InertiaForm<SaleReturnForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
