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
                                v-model="form.document_type_id"
                                name="document_type_id"
                                :label="$t('craftable-pro', 'Document Type')"
                                mode="single"
                                :options="$page.props.document_types ?? []"
                                options-value-prop="id"
                                options-label="name"
                                :searchable="true"
                            />
                            <Multiselect
                                v-model="form.sale_order_id"
                                name="sale_order_id"
                                :label="$t('craftable-pro', 'Sale Order')"
                                mode="single"
                                :options="$page.props.order_headers ?? []"
                                options-value-prop="id"
                                options-label="order_no"
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
                            <Multiselect
                                v-model="form.stock_return_id"
                                name="stock_return_id"
                                :label="$t('craftable-pro', 'Stock Return')"
                                mode="single"
                                :options="$page.props.stock_returns ?? []"
                                options-value-prop="id"
                                options-label="id"
                                :searchable="true"
                            />
                            <Multiselect
                                v-model="form.inventory_count_id"
                                name="inventory_count_id"
                                :label="$t('craftable-pro', 'Inventory Count')"
                                mode="single"
                                :options="$page.props.inventory_counts ?? []"
                                options-value-prop="id"
                                options-label="id"
                                :searchable="true"
                            />
                            <Multiselect
                                v-model="form.loss_and_damage_id"
                                name="loss_and_damage_id"
                                :label="$t('craftable-pro', 'Loss And Damage')"
                                mode="single"
                                :options="$page.props.loss_and_damages ?? []"
                                options-value-prop="id"
                                options-label="id"
                                :searchable="true"
                            />
                            <Multiselect
                                v-model="form.created_by"
                                name="created_by"
                                :label="$t('craftable-pro', 'Created By')"
                                mode="single"
                                :options="$page.props.craftable_pro_users ?? []"
                                options-value-prop="id"
                                options-label="email"
                                :searchable="true"
                            />
                            <TextInput
                                v-model="form.number"
                                name="number"
                                :label="$t('craftable-pro', 'Number')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.external_number"
                                name="external_number"
                                :label="$t('craftable-pro', 'External Number')"
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
import type { DocumentForm } from "./types";



interface Props {
    form: InertiaForm<DocumentForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
