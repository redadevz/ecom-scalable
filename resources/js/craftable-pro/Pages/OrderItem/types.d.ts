

export type OrderItem = {
    id: string | number;
    order_id: string | number;
    product_id: string | number;
    product_name: string;
    quantity: integer;
    unit_price: decimal;
    
};

export type OrderItemForm = {
    order_id: string | number;
    product_id: string | number;
    product_name: string;
    quantity: integer;
    unit_price: decimal;
};
