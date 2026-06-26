

export type Refund = {
    id: string | number;
    sale_return_id: string | number;
    payment_method_id: string | number;
    refund_no: string;
    amount: decimal;
    cash_paid: decimal;
    cash_change: decimal;
    refund_time: string;
    comments: string;
    
};

export type RefundForm = {
    sale_return_id: string | number;
    payment_method_id: string | number;
    refund_no: string;
    amount: decimal;
    cash_paid: decimal;
    cash_change: decimal;
    refund_time: string;
    comments: string;
};
