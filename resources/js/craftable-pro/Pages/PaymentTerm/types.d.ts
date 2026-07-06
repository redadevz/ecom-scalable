

type RelationRef = { id: string | number; name: string } | null;

export type PaymentTerm = {
    id: string | number;
    sale_channel_id: string | number;
    delivery_type_id: string | number;
    payment_method_id: string | number;
    payment_time_id: string | number;
    is_allowed: boolean;
    is_active: boolean;
    comments: string;
    created_at?: string;
    saleChannel?: RelationRef;
    deliveryType?: RelationRef;
    paymentMethod?: RelationRef;
    paymentTime?: RelationRef;
};

export type PaymentTermForm = {
    sale_channel_id: string | number;
    delivery_type_id: string | number;
    payment_method_id: string | number;
    payment_time_id: string | number;
    is_allowed: boolean;
    is_active: boolean;
    comments: string;
};
