

export type Purchase = {
    id: string | number;
    store_id: string | number;
    supplier_id: string | number;
    entry_stock_time: string;
    description: string;
    is_paid: boolean;
    comments: string;
    
};

export type PurchaseForm = {
    store_id: string | number;
    supplier_id: string | number;
    entry_stock_time: string;
    description: string;
    is_paid: boolean;
    comments: string;
};
