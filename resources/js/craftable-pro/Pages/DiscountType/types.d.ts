

export type DiscountType = {
    id: string | number;
    store_id: string | number;
    loyalty_card_type_id: string | number;
    name: string;
    description: string;
    is_percentage: boolean;
    value: decimal;
    coupon_code: string;
    min_order_value: decimal;
    min_item_quantity: integer;
    apply_to_all: boolean;
    apply_to_next: boolean;
    max_discount_value: decimal;
    start_time: string;
    end_time: string;
    is_active: boolean;
    comments: string;
    
};

export type DiscountTypeForm = {
    store_id: string | number;
    loyalty_card_type_id: string | number;
    name: string;
    description: string;
    is_percentage: boolean;
    value: decimal;
    coupon_code: string;
    min_order_value: decimal;
    min_item_quantity: integer;
    apply_to_all: boolean;
    apply_to_next: boolean;
    max_discount_value: decimal;
    start_time: string;
    end_time: string;
    is_active: boolean;
    comments: string;
};
