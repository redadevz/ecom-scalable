<template>
    <PageContent>
        <div class="mx-auto max-w-3xl 2xl:max-w-4xl">
            

            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1 space-y-4">
                    <Card>
                        <div class="space-y-4">
                            <TextInput
                                v-model="form.item_id"
                                name="item_id"
                                :label="$t('craftable-pro', 'Item Id')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.store_id"
                                name="store_id"
                                :label="$t('craftable-pro', 'Store Id')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.created_by"
                                name="created_by"
                                :label="$t('craftable-pro', 'Created By')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.description"
                                name="description"
                                :label="$t('craftable-pro', 'Description')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.current_item_cost"
                                name="current_item_cost"
                                :label="$t('craftable-pro', 'Current Item Cost')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.markup_percentage"
                                name="markup_percentage"
                                :label="$t('craftable-pro', 'Markup Percentage')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.price_before_tax"
                                name="price_before_tax"
                                :label="$t('craftable-pro', 'Price Before Tax')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.tax_value"
                                name="tax_value"
                                :label="$t('craftable-pro', 'Tax Value')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.price_after_tax"
                                name="price_after_tax"
                                :label="$t('craftable-pro', 'Price After Tax')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.sale_price"
                                name="sale_price"
                                :label="$t('craftable-pro', 'Sale Price')"
                                type="text"
                            />
                            <Checkbox
                                v-model="form.price_change_allowed"
                                name="price_change_allowed"
                                :label="$t('craftable-pro', 'Price Change Allowed')"
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
import type { PriceForm } from "./types";



interface Props {
    form: InertiaForm<PriceForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
