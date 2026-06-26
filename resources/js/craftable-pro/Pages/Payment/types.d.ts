

export type Payment = {
    id: string | number;
    invoice_id: string | number;
    payment_method_id: string | number;
    payment_no: string;
    amount: decimal;
    cash_paid: decimal;
    cash_change: decimal;
    payment_time: string;
    comments: string;
    
};

export type PaymentForm = {
    invoice_id: string | number;
    payment_method_id: string | number;
    payment_no: string;
    amount: decimal;
    cash_paid: decimal;
    cash_change: decimal;
    payment_time: string;
    comments: string;
};
