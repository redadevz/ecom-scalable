<template>
    <PageContent>
        <div class="mx-auto max-w-3xl 2xl:max-w-4xl">
            

            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1 space-y-4">
                    <Card>
                        <div class="space-y-4">
                            <Multiselect
                                v-model="form.discount_id"
                                name="discount_id"
                                :label="$t('craftable-pro', 'Discount')"
                                mode="single"
                                :options="$page.props.discounts ?? []"
                                options-value-prop="id"
                                options-label="id"
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
                            <TextInput
                                v-model="form.value"
                                name="value"
                                :label="$t('craftable-pro', 'Value')"
                                type="number"
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
import type { OrderDiscountForm } from "./types";



interface Props {
    form: InertiaForm<OrderDiscountForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
