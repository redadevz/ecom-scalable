<template>
    <PageContent>
        <div class="mx-auto max-w-5xl space-y-6">

            <Card title="Relations">
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                    <Multiselect
                        v-model="form.stock_return_id"
                        name="stock_return_id"
                        label="Stock Return"
                        mode="single"
                        :options="$page.props.stock_returns ?? []"
                        options-value-prop="id"
                        options-label="id"
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
                        v-model="form.quantity"
                        name="quantity"
                        label="Quantity"
                        type="number"
                    />
                    <TextInput
                        v-model="form.supplier_price_before_tax"
                        name="supplier_price_before_tax"
                        label="Supplier Price Before Tax"
                        type="number"
                    />
                    <TextInput
                        v-model="form.supplier_tax_value"
                        name="supplier_tax_value"
                        label="Supplier Tax Value"
                        type="number"
                    />
                    <TextInput
                        v-model="form.supplier_price_after_tax"
                        name="supplier_price_after_tax"
                        label="Supplier Price After Tax"
                        type="number"
                    />
                    <TextInput
                        v-model="form.supplier_discount_value"
                        name="supplier_discount_value"
                        label="Supplier Discount Value"
                        type="number"
                    />
                    <TextInput
                        v-model="form.return_amount"
                        name="return_amount"
                        label="Return Amount"
                        type="number"
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
