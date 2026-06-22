<template>
    <PageContent>
        <div class="mx-auto max-w-3xl 2xl:max-w-4xl">
            

            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1 space-y-4">
                    <Card>
                        <div class="space-y-4">
                            <TextInput
                                v-model="form.order_id"
                                name="order_id"
                                :label="$t('craftable-pro', 'Order Id')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.product_id"
                                name="product_id"
                                :label="$t('craftable-pro', 'Product Id')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.product_name"
                                name="product_name"
                                :label="$t('craftable-pro', 'Product Name')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.quantity"
                                name="quantity"
                                :label="$t('craftable-pro', 'Quantity')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.unit_price"
                                name="unit_price"
                                :label="$t('craftable-pro', 'Unit Price')"
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
import type { OrderItemForm } from "./types";



interface Props {
    form: InertiaForm<OrderItemForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
