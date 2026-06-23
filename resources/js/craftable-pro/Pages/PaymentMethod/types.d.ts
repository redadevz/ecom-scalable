

export type PaymentMethod = {
    id: string | number;
    name: string;
    code: string;
    sequence_no: integer;
    is_active: boolean;
    is_customer_required: boolean;
    description: string;
    
};

export type PaymentMethodForm = {
    name: string;
    code: string;
    sequence_no: integer;
    is_active: boolean;
    is_customer_required: boolean;
    description: string;
};
