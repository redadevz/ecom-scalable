

export type OrderLine = {
    id: string | number;
    store_id: string | number;
    order_id: string | number;
    item_id: string | number;
    line_no: string;
    description: string;
    customer_notes: string;
    quantity: integer;
    current_item_cost: decimal;
    markup_percentage: integer;
    price_before_tax: decimal;
    tax_value: decimal;
    price_after_tax: decimal;
    price_before_discount: decimal;
    discount_value: decimal;
    price_after_discount: decimal;
    price_adjustment: decimal;
    price_adjustment_reason: string;
    price: decimal;
    is_canceled: boolean;
    canceled_time: string;
    cancel_reason: string;
    return_required: boolean;
    return_quantity: integer;
    return_time: string;
    customer_review: string;
    customer_like: boolean;
    comments: string;
    
};

export type OrderLineForm = {
    store_id: string | number;
    order_id: string | number;
    item_id: string | number;
    line_no: string;
    description: string;
    customer_notes: string;
    quantity: integer;
    current_item_cost: decimal;
    markup_percentage: integer;
    price_before_tax: decimal;
    tax_value: decimal;
    price_after_tax: decimal;
    price_before_discount: decimal;
    discount_value: decimal;
    price_after_discount: decimal;
    price_adjustment: decimal;
    price_adjustment_reason: string;
    price: decimal;
    is_canceled: boolean;
    canceled_time: string;
    cancel_reason: string;
    return_required: boolean;
    return_quantity: integer;
    return_time: string;
    customer_review: string;
    customer_like: boolean;
    comments: string;
};
