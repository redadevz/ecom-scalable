

export type CartItem = {
    id: string | number;
    cart_id: string | number;
    product_id: string | number;
    quantity: integer;
    unit_price: decimal;
    
};

export type CartItemForm = {
    cart_id: string | number;
    product_id: string | number;
    quantity: integer;
    unit_price: decimal;
};
