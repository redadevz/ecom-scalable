<template>
    <PageContent>
        <div class="mx-auto max-w-3xl 2xl:max-w-4xl">
            

            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1 space-y-4">
                    <Card>
                        <div class="space-y-4">
                            <TextInput
                                v-model="form.loyalty_card_type_id"
                                name="loyalty_card_type_id"
                                :label="$t('craftable-pro', 'Loyalty Card Type Id')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.customer_id"
                                name="customer_id"
                                :label="$t('craftable-pro', 'Customer Id')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.code"
                                name="code"
                                :label="$t('craftable-pro', 'Code')"
                                type="text"
                            />
                            <Checkbox
                                v-model="form.is_active"
                                name="is_active"
                                :label="$t('craftable-pro', 'Is Active')"
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
import type { LoyaltyCardForm } from "./types";



interface Props {
    form: InertiaForm<LoyaltyCardForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
