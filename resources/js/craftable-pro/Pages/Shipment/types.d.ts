

export type Shipment = {
    id: string | number;
    store_id: string | number;
    order_id: string | number;
    shipment_city_id: string | number;
    picked_up_by: string | number;
    shipment_address: string;
    gps_location: string;
    postal_code: string;
    shipment_notes: string;
    picked_up_time: string;
    shipped_time: string;
    comments: string;
    created_at?: string;
    order?: { id: string | number; order_no: string } | null;
    store?: { id: string | number; name: string } | null;
    shipment_city?: { id: string | number; name: string } | null;
};

export type ShipmentForm = {
    store_id: string | number;
    order_id: string | number;
    shipment_city_id: string | number;
    picked_up_by: string | number;
    shipment_address: string;
    gps_location: string;
    postal_code: string;
    shipment_notes: string;
    picked_up_time: string;
    shipped_time: string;
    comments: string;
};
