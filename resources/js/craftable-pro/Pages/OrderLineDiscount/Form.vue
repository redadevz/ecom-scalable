<template>
    <PageContent>
        <div class="mx-auto max-w-5xl space-y-6">

            <Card title="Relations">
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                    <Multiselect
                        v-model="form.discount_id"
                        name="discount_id"
                        label="Discount"
                        mode="single"
                        :options="$page.props.discounts ?? []"
                        options-value-prop="id"
                        options-label="description"
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
                </div>
            </Card>

            <Card title="Details">
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                    <TextInput
                        v-model="form.value"
                        name="value"
                        label="Value"
                        type="number"
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
import type { OrderLineDiscountForm } from "./types";



interface Props {
    form: InertiaForm<OrderLineDiscountForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
