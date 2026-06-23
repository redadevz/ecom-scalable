

export type Discount = {
    id: string | number;
    discount_type_id: string | number;
    item_category_id: string | number;
    item_id: string | number;
    description: string;
    comments: string;
    
};

export type DiscountForm = {
    discount_type_id: string | number;
    item_category_id: string | number;
    item_id: string | number;
    description: string;
    comments: string;
};
