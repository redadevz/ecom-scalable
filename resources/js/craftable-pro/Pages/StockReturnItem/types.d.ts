

export type StockReturnItem = {
    id: string | number;
    stock_return_id: string | number;
    item_id: string | number;
    quantity: integer;
    supplier_price_before_tax: decimal;
    supplier_tax_value: decimal;
    supplier_price_after_tax: decimal;
    supplier_discount_value: decimal;
    return_amount: decimal;
    description: string;
    comments: string;
    
};

export type StockReturnItemForm = {
    stock_return_id: string | number;
    item_id: string | number;
    quantity: integer;
    supplier_price_before_tax: decimal;
    supplier_tax_value: decimal;
    supplier_price_after_tax: decimal;
    supplier_discount_value: decimal;
    return_amount: decimal;
    description: string;
    comments: string;
};
