<template>
    <PageContent>
        <div class="mx-auto max-w-3xl 2xl:max-w-4xl">
            

            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1 space-y-4">
                    <Card>
                        <div class="space-y-4">
                            <TextInput
                                v-model="form.name"
                                name="name"
                                :label="$t('craftable-pro', 'Name')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.slug"
                                name="slug"
                                :label="$t('craftable-pro', 'Slug')"
                                type="text"
                            />
                            <TextArea
                                v-model="form.description"
                                name="description"
                                :label="$t('craftable-pro', 'Description')"
                            />
                            <TextInput
                                v-model="form.price"
                                name="price"
                                :label="$t('craftable-pro', 'Price')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.stock"
                                name="stock"
                                :label="$t('craftable-pro', 'Stock')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.category"
                                name="category"
                                :label="$t('craftable-pro', 'Category')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.image_url"
                                name="image_url"
                                :label="$t('craftable-pro', 'Image Url')"
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
import type { ProductForm } from "./types";



interface Props {
    form: InertiaForm<ProductForm>;
    submit: void;
    craftableProUserOptions: Array<{value: string|number, label: string}>
}

const props = defineProps<Props>();

</script>
