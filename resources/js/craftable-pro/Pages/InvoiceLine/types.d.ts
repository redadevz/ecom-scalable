

export type InvoiceLine = {
    id: string | number;
    invoice_id: string | number;
    order_line_id: string | number;
    line_no: string;
    comments: string;
    
};

export type InvoiceLineForm = {
    invoice_id: string | number;
    order_line_id: string | number;
    line_no: string;
    comments: string;
};
