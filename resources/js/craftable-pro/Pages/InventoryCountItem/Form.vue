<template>
    <PageContent>
        <div class="mx-auto max-w-3xl 2xl:max-w-4xl">
            

            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1 space-y-4">
                    <Card>
                        <div class="space-y-4">
                            <TextInput
                                v-model="form.inventory_count_id"
                                name="inventory_count_id"
                                :label="$t('craftable-pro', 'Inventory Count Id')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.item_id"
                                name="item_id"
                                :label="$t('craftable-pro', 'Item Id')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.quantity_counted"
                                name="quantity_counted"
                                :label="$t('craftable-pro', 'Quantity Counted')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.quantity_expected"
                                name="quantity_expected"
                                :label="$t('craftable-pro', 'Quantity Expected')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.quantity_change"
                                name="quantity_change"
                                :label="$t('craftable-pro', 'Quantity Change')"
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
import type { InventoryCountItemForm } from "./types";



interface Props {
    form: InertiaForm<InventoryCountItemForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
