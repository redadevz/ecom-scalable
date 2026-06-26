

export type Document = {
    id: string | number;
    store_id: string | number;
    document_type_id: string | number;
    sale_order_id: string | number;
    purchase_id: string | number;
    stock_return_id: string | number;
    inventory_count_id: string | number;
    loss_and_damage_id: string | number;
    created_by: string | number;
    number: string;
    external_number: string;
    description: string;
    comments: string;
    
};

export type DocumentForm = {
    store_id: string | number;
    document_type_id: string | number;
    sale_order_id: string | number;
    purchase_id: string | number;
    stock_return_id: string | number;
    inventory_count_id: string | number;
    loss_and_damage_id: string | number;
    created_by: string | number;
    number: string;
    external_number: string;
    description: string;
    comments: string;
};
