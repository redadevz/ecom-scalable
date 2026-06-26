

export type Invoice = {
    id: string | number;
    order_id: string | number;
    invoice_no: string;
    is_paid: boolean;
    payment_time: string;
    comments: string;
    
};

export type InvoiceForm = {
    order_id: string | number;
    invoice_no: string;
    is_paid: boolean;
    payment_time: string;
    comments: string;
};
