

export type Payment = {
    id: string | number;
    order_id: string | number;
    provider: string;
    provider_reference: string;
    status: string;
    amount: decimal;
    currency: string;
    
};

export type PaymentForm = {
    order_id: string | number;
    provider: string;
    provider_reference: string;
    status: string;
    amount: decimal;
    currency: string;
};
