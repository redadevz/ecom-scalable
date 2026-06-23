

export type Price = {
    id: string | number;
    item_id: string | number;
    store_id: string | number;
    created_by: string | number;
    description: string;
    current_item_cost: decimal;
    markup_percentage: integer;
    price_before_tax: decimal;
    tax_value: decimal;
    price_after_tax: decimal;
    sale_price: decimal;
    price_change_allowed: boolean;
    start_time: string;
    end_time: string;
    is_active: boolean;
    comments: string;
    
};

export type PriceForm = {
    item_id: string | number;
    store_id: string | number;
    created_by: string | number;
    description: string;
    current_item_cost: decimal;
    markup_percentage: integer;
    price_before_tax: decimal;
    tax_value: decimal;
    price_after_tax: decimal;
    sale_price: decimal;
    price_change_allowed: boolean;
    start_time: string;
    end_time: string;
    is_active: boolean;
    comments: string;
};
