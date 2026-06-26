

export type SaleReturn = {
    id: string | number;
    store_id: string | number;
    order_id: string | number;
    entry_stock_time: string;
    is_refund_required: boolean;
    refund_amount: decimal;
    is_refunded: boolean;
    refund_time: string;
    description: string;
    comments: string;
    
};

export type SaleReturnForm = {
    store_id: string | number;
    order_id: string | number;
    entry_stock_time: string;
    is_refund_required: boolean;
    refund_amount: decimal;
    is_refunded: boolean;
    refund_time: string;
    description: string;
    comments: string;
};
