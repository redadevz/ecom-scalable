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
                                v-model="form.order_id"
                                name="order_id"
                                :label="$t('craftable-pro', 'Order')"
                                mode="single"
                                :options="$page.props.order_headers ?? []"
                                options-value-prop="id"
                                options-label="order_no"
                                :searchable="true"
                            />
                            <Multiselect
                                v-model="form.shipment_city_id"
                                name="shipment_city_id"
                                :label="$t('craftable-pro', 'Shipment City')"
                                mode="single"
                                :options="$page.props.cities ?? []"
                                options-value-prop="id"
                                options-label="name"
                                :searchable="true"
                            />
                            <Multiselect
                                v-model="form.picked_up_by"
                                name="picked_up_by"
                                :label="$t('craftable-pro', 'Picked Up By')"
                                mode="single"
                                :options="$page.props.craftable_pro_users ?? []"
                                options-value-prop="id"
                                options-label="email"
                                :searchable="true"
                            />
                            <TextInput
                                v-model="form.shipment_address"
                                name="shipment_address"
                                :label="$t('craftable-pro', 'Shipment Address')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.gps_location"
                                name="gps_location"
                                :label="$t('craftable-pro', 'Gps Location')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.postal_code"
                                name="postal_code"
                                :label="$t('craftable-pro', 'Postal Code')"
                                type="text"
                            />
                            <TextInput
                                v-model="form.shipment_notes"
                                name="shipment_notes"
                                :label="$t('craftable-pro', 'Shipment Notes')"
                                type="text"
                            />
                            <DatePicker
                                v-model="form.picked_up_time"
                                name="picked_up_time"
                                mode="dateTime"
                                :label="$t('craftable-pro', 'Picked Up Time')"
                                
                            />
                            <DatePicker
                                v-model="form.shipped_time"
                                name="shipped_time"
                                mode="dateTime"
                                :label="$t('craftable-pro', 'Shipped Time')"
                                
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
import type { ShipmentForm } from "./types";



interface Props {
    form: InertiaForm<ShipmentForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
