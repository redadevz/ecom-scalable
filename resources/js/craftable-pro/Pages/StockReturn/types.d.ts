

export type StockReturn = {
    id: string | number;
    store_id: string | number;
    purchase_id: string | number;
    exit_stock_time: string;
    description: string;
    is_paid: boolean;
    comments: string;
    
};

export type StockReturnForm = {
    store_id: string | number;
    purchase_id: string | number;
    exit_stock_time: string;
    description: string;
    is_paid: boolean;
    comments: string;
};
