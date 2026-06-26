<template>
    <PageContent>
        <div class="mx-auto max-w-3xl 2xl:max-w-4xl">
            

            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1 space-y-4">
                    <Card>
                        <div class="space-y-4">
                            <TextInput
                                v-model="form.invoice_id"
                                name="invoice_id"
                                :label="$t('craftable-pro', 'Invoice Id')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.order_line_id"
                                name="order_line_id"
                                :label="$t('craftable-pro', 'Order Line Id')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.line_no"
                                name="line_no"
                                :label="$t('craftable-pro', 'Line No')"
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
import type { InvoiceLineForm } from "./types";



interface Props {
    form: InertiaForm<InvoiceLineForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
