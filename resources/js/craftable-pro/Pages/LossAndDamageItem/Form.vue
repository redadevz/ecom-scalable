<template>
    <PageContent>
        <div class="mx-auto max-w-3xl 2xl:max-w-4xl">
            

            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1 space-y-4">
                    <Card>
                        <div class="space-y-4">
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
                                v-model="form.item_id"
                                name="item_id"
                                :label="$t('craftable-pro', 'Item')"
                                mode="single"
                                :options="$page.props.items ?? []"
                                options-value-prop="id"
                                options-label="name"
                                :searchable="true"
                            />
                            <TextInput
                                v-model="form.quantity"
                                name="quantity"
                                :label="$t('craftable-pro', 'Quantity')"
                                type="number"
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
import type { LossAndDamageItemForm } from "./types";



interface Props {
    form: InertiaForm<LossAndDamageItemForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
