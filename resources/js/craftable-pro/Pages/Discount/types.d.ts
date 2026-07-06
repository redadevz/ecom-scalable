

export type Discount = {
    id: string | number;
    discount_type_id: string | number;
    item_category_id: string | number;
    item_id: string | number;
    description: string;
    comments: string;
    created_at?: string;
    discount_type?: { id: string | number; name: string } | null;
    item_category?: { id: string | number; name: string } | null;
    item?: { id: string | number; name: string } | null;
};

export type DiscountForm = {
    discount_type_id: string | number;
    item_category_id: string | number;
    item_id: string | number;
    description: string;
    comments: string;
};
