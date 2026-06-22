<template>
    <PageContent>
        <div class="mx-auto max-w-3xl 2xl:max-w-4xl">
            

            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1 space-y-4">
                    <Card>
                        <div class="space-y-4">
                            <TextInput
                                v-model="form.user_id"
                                name="user_id"
                                :label="$t('craftable-pro', 'User Id')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.status"
                                name="status"
                                :label="$t('craftable-pro', 'Status')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.total"
                                name="total"
                                :label="$t('craftable-pro', 'Total')"
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
import type { OrderForm } from "./types";



interface Props {
    form: InertiaForm<OrderForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
