<template>
    <PageContent>
        <div class="mx-auto max-w-5xl space-y-6">

            <Card title="Relations">
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
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
                        v-model="form.created_by"
                        name="created_by"
                        label="Created By"
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
                    <div class="sm:col-span-2">
                        <TextInput
                            v-model="form.description"
                            name="description"
                            label="Description"
                            type="text"
                        />
                    </div>
                    <TextInput
                        v-model="form.current_item_cost"
                        name="current_item_cost"
                        label="Current Item Cost"
                        type="number"
                    />
                    <TextInput
                        v-model="form.markup_percentage"
                        name="markup_percentage"
                        label="Markup Percentage"
                        type="number"
                    />
                    <TextInput
                        v-model="form.price_before_tax"
                        name="price_before_tax"
                        label="Price Before Tax"
                        type="number"
                    />
                    <TextInput
                        v-model="form.tax_value"
                        name="tax_value"
                        label="Tax Value"
                        type="number"
                    />
                    <TextInput
                        v-model="form.price_after_tax"
                        name="price_after_tax"
                        label="Price After Tax"
                        type="number"
                    />
                    <TextInput
                        v-model="form.sale_price"
                        name="sale_price"
                        label="Sale Price"
                        type="number"
                    />
                    <DatePicker
                        v-model="form.start_time"
                        name="start_time"
                        mode="dateTime"
                        label="Start Time"
                    />
                    <DatePicker
                        v-model="form.end_time"
                        name="end_time"
                        mode="dateTime"
                        label="End Time"
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
                        v-model="form.price_change_allowed"
                        name="price_change_allowed"
                        label="Price Change Allowed"
                    />
                    <Checkbox
                        v-model="form.is_active"
                        name="is_active"
                        label="Is Active"
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
import type { PriceForm } from "./types";



interface Props {
    form: InertiaForm<PriceForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
