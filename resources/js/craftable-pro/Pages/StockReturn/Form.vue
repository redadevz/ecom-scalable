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
                                v-model="form.purchase_id"
                                name="purchase_id"
                                :label="$t('craftable-pro', 'Purchase')"
                                mode="single"
                                :options="$page.props.purchases ?? []"
                                options-value-prop="id"
                                options-label="id"
                                :searchable="true"
                            />
                            <DatePicker
                                v-model="form.exit_stock_time"
                                name="exit_stock_time"
                                mode="dateTime"
                                :label="$t('craftable-pro', 'Exit Stock Time')"
                                
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
import type { StockReturnForm } from "./types";



interface Props {
    form: InertiaForm<StockReturnForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
