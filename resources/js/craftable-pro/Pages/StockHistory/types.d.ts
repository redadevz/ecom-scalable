

export type StockHistory = {
    id: string | number;
    store_id: string | number;
    item_id: string | number;
    document_id: string | number;
    initial_stock_quantity: integer;
    initial_item_cost: decimal;
    is_stock_entry: boolean;
    quantity: integer;
    current_stock_quantity: integer;
    current_item_cost: decimal;
    description: string;
    comments: string;
    
};

export type StockHistoryForm = {
    store_id: string | number;
    item_id: string | number;
    document_id: string | number;
    initial_stock_quantity: integer;
    initial_item_cost: decimal;
    is_stock_entry: boolean;
    quantity: integer;
    current_stock_quantity: integer;
    current_item_cost: decimal;
    description: string;
    comments: string;
};
