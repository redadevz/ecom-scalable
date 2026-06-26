<template>
    <PageContent>
        <div class="mx-auto max-w-3xl 2xl:max-w-4xl">
            

            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1 space-y-4">
                    <Card>
                        <div class="space-y-4">
                            <TextInput
                                v-model="form.stock_return_id"
                                name="stock_return_id"
                                :label="$t('craftable-pro', 'Stock Return Id')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.item_id"
                                name="item_id"
                                :label="$t('craftable-pro', 'Item Id')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.quantity"
                                name="quantity"
                                :label="$t('craftable-pro', 'Quantity')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.supplier_price_before_tax"
                                name="supplier_price_before_tax"
                                :label="$t('craftable-pro', 'Supplier Price Before Tax')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.supplier_tax_value"
                                name="supplier_tax_value"
                                :label="$t('craftable-pro', 'Supplier Tax Value')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.supplier_price_after_tax"
                                name="supplier_price_after_tax"
                                :label="$t('craftable-pro', 'Supplier Price After Tax')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.supplier_discount_value"
                                name="supplier_discount_value"
                                :label="$t('craftable-pro', 'Supplier Discount Value')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.return_amount"
                                name="return_amount"
                                :label="$t('craftable-pro', 'Return Amount')"
                                type="text"
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
import type { StockReturnItemForm } from "./types";



interface Props {
    form: InertiaForm<StockReturnItemForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
