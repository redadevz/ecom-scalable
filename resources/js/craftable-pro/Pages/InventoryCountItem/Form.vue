<template>
    <PageContent>
        <div class="mx-auto max-w-5xl space-y-6">

            <Card title="Relations">
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
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
                        v-model="form.quantity_counted"
                        name="quantity_counted"
                        label="Quantity Counted"
                        type="number"
                    />
                    <TextInput
                        v-model="form.quantity_expected"
                        name="quantity_expected"
                        label="Quantity Expected"
                        type="number"
                    />
                    <TextInput
                        v-model="form.quantity_change"
                        name="quantity_change"
                        label="Quantity Change"
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
import type { InventoryCountItemForm } from "./types";



interface Props {
    form: InertiaForm<InventoryCountItemForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
