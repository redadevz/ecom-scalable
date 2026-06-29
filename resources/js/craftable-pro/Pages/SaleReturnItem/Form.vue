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
                        options-label="id"
                        :searchable="true"
                    />
                    <Multiselect
                        v-model="form.order_line_id"
                        name="order_line_id"
                        label="Order Line"
                        mode="single"
                        :options="$page.props.order_lines ?? []"
                        options-value-prop="id"
                        options-label="line_no"
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
                    <TextInput
                        v-model="form.quantity"
                        name="quantity"
                        label="Quantity"
                        type="number"
                    />
                    <TextInput
                        v-model="form.return_quantity"
                        name="return_quantity"
                        label="Return Quantity"
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
import type { SaleReturnItemForm } from "./types";



interface Props {
    form: InertiaForm<SaleReturnItemForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
