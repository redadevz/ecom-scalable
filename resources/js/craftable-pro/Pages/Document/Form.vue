<template>
    <PageContent>
        <div class="mx-auto max-w-5xl space-y-6">

            <Card title="Relations">
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
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
                        v-model="form.document_type_id"
                        name="document_type_id"
                        label="Document Type"
                        mode="single"
                        :options="$page.props.document_types ?? []"
                        options-value-prop="id"
                        options-label="name"
                        :searchable="true"
                    />
                    <Multiselect
                        v-model="form.sale_order_id"
                        name="sale_order_id"
                        label="Sale Order"
                        mode="single"
                        :options="$page.props.order_headers ?? []"
                        options-value-prop="id"
                        options-label="order_no"
                        :searchable="true"
                    />
                    <Multiselect
                        v-model="form.purchase_id"
                        name="purchase_id"
                        label="Purchase"
                        mode="single"
                        :options="$page.props.purchases ?? []"
                        options-value-prop="id"
                        options-label="id"
                        :searchable="true"
                    />
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
                        v-model="form.inventory_count_id"
                        name="inventory_count_id"
                        label="Inventory Count"
                        mode="single"
                        :options="$page.props.inventory_counts ?? []"
                        options-value-prop="id"
                        options-label="id"
                        :searchable="true"
                    />
                    <Multiselect
                        v-model="form.loss_and_damage_id"
                        name="loss_and_damage_id"
                        label="Loss And Damage"
                        mode="single"
                        :options="$page.props.loss_and_damages ?? []"
                        options-value-prop="id"
                        options-label="id"
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
                    <TextInput
                        v-model="form.number"
                        name="number"
                        label="Number"
                        type="text"
                    />
                    <TextInput
                        v-model="form.external_number"
                        name="external_number"
                        label="External Number"
                        type="text"
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
import type { DocumentForm } from "./types";



interface Props {
    form: InertiaForm<DocumentForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
