<template>
    <PageContent>
        <div class="mx-auto max-w-3xl 2xl:max-w-4xl">
            

            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1 space-y-4">
                    <Card>
                        <div class="space-y-4">
                            <TextInput
                                v-model="form.store_id"
                                name="store_id"
                                :label="$t('craftable-pro', 'Store Id')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.supplier_id"
                                name="supplier_id"
                                :label="$t('craftable-pro', 'Supplier Id')"
                                type="text"
                            />
                            <DatePicker
                                v-model="form.entry_stock_time"
                                name="entry_stock_time"
                                mode="dateTime"
                                :label="$t('craftable-pro', 'Entry Stock Time')"
                                
                            />
                            <TextInput
                                v-model="form.description"
                                name="description"
                                :label="$t('craftable-pro', 'Description')"
                                type="text"
                            />
                            <Checkbox
                                v-model="form.is_paid"
                                name="is_paid"
                                :label="$t('craftable-pro', 'Is Paid')"
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
import type { PurchaseForm } from "./types";



interface Props {
    form: InertiaForm<PurchaseForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
