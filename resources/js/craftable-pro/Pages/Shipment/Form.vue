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
                        v-model="form.order_id"
                        name="order_id"
                        label="Order"
                        mode="single"
                        :options="$page.props.order_headers ?? []"
                        options-value-prop="id"
                        options-label="order_no"
                        :searchable="true"
                    />
                    <Multiselect
                        v-model="form.shipment_city_id"
                        name="shipment_city_id"
                        label="Shipment City"
                        mode="single"
                        :options="$page.props.cities ?? []"
                        options-value-prop="id"
                        options-label="name"
                        :searchable="true"
                    />
                    <Multiselect
                        v-model="form.picked_up_by"
                        name="picked_up_by"
                        label="Picked Up By"
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
                    <div class="sm:col-span-2">
                        <TextInput
                            v-model="form.shipment_address"
                            name="shipment_address"
                            label="Shipment Address"
                            type="text"
                        />
                    </div>
                    <TextInput
                        v-model="form.gps_location"
                        name="gps_location"
                        label="Gps Location"
                        type="text"
                    />
                    <TextInput
                        v-model="form.postal_code"
                        name="postal_code"
                        label="Postal Code"
                        type="text"
                    />
                    <TextInput
                        v-model="form.shipment_notes"
                        name="shipment_notes"
                        label="Shipment Notes"
                        type="text"
                    />
                    <DatePicker
                        v-model="form.picked_up_time"
                        name="picked_up_time"
                        mode="dateTime"
                        label="Picked Up Time"
                    />
                    <DatePicker
                        v-model="form.shipped_time"
                        name="shipped_time"
                        mode="dateTime"
                        label="Shipped Time"
                    />
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
import type { ShipmentForm } from "./types";



interface Props {
    form: InertiaForm<ShipmentForm>;
    submit: void;
    
}

const props = defineProps<Props>();

</script>
