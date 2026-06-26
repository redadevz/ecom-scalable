

export type OrderLineDiscount = {
    id: string | number;
    discount_id: string | number;
    order_line_id: string | number;
    value: decimal;
    comments: string;
    
};

export type OrderLineDiscountForm = {
    discount_id: string | number;
    order_line_id: string | number;
    value: decimal;
    comments: string;
};
