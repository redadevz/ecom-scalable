<template>
    <PageContent>
        <div class="mx-auto max-w-3xl 2xl:max-w-4xl">
            

            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1 space-y-4">
                    <Card>
                        <div class="space-y-4">
                            <Multiselect
                                v-model="form.sale_channel_id"
                                name="sale_channel_id"
                                :label="$t('craftable-pro', 'Sale Channel')"
                                mode="single"
                                :options="$page.props.sale_channels ?? []"
                                options-value-prop="id"
                                options-label="name"
                                :searchable="true"
                            />
                            <Multiselect
                                v-model="form.delivery_type_id"
                                name="delivery_type_id"
                                :label="$t('craftable-pro', 'Delivery Type')"
                                mode="single"
                                :options="$page.props.delivery_types ?? []"
                                options-value-prop="id"
                                options-label="name"
                                :searchable="true"
                            />
                            <Multiselect
                                v-model="form.payment_method_id"
                                name="payment_method_id"
                                :label="$t('craftable-pro', 'Payment Method')"
                                mode="single"
                                :options="$page.props.payment_methods ?? []"
                                options-value-prop="id"
                                options-label="name"
                                :searchable="true"
                            />
                            <Multiselect
                                v-model="form.payment_time_id"
                                name="payment_time_id"
                                :label="$t('craftable-pro', 'Payment Time')"
                                mode="single"
                                :options="$page.props.payment_times ?? []"
                                options-value-prop="id"
                                options-label="name"
                                :searchable="true"
                            />
                            <Checkbox
                                v-model="form.is_allowed"
                                name="is_allowed"
                                :label="$t('craftable-pro', 'Is Allowed')"
                            />
                            <Checkbox
                                v-model="form.is_active"
                                name="is_active"
                                :label="$t('craftable-pro', 'Is Active')"
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
import type { PaymentTermForm } from "./types";



interface Props {
    form: InertiaForm<PaymentTermForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
