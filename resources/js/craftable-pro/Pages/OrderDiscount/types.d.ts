

export type OrderDiscount = {
    id: string | number;
    discount_id: string | number;
    order_id: string | number;
    value: decimal;
    comments: string;
    
};

export type OrderDiscountForm = {
    discount_id: string | number;
    order_id: string | number;
    value: decimal;
    comments: string;
};
