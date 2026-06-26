

export type PurchaseItem = {
    id: string | number;
    purchase_id: string | number;
    item_id: string | number;
    supplier_price_before_tax: decimal;
    supplier_tax_value: decimal;
    supplier_price_after_tax: decimal;
    supplier_discount_value: decimal;
    quantity: integer;
    return_amount: decimal;
    description: string;
    comments: string;
    
};

export type PurchaseItemForm = {
    purchase_id: string | number;
    item_id: string | number;
    supplier_price_before_tax: decimal;
    supplier_tax_value: decimal;
    supplier_price_after_tax: decimal;
    supplier_discount_value: decimal;
    quantity: integer;
    return_amount: decimal;
    description: string;
    comments: string;
};
