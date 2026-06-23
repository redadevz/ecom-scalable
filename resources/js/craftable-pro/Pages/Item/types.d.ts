

export type Item = {
    id: string | number;
    store_id: string | number;
    item_category_id: string | number;
    supplier_id: string | number;
    measure_unit_id: string | number;
    sku_code: string;
    name: string;
    description: string;
    is_service: boolean;
    in_stock: boolean;
    using_default_quantity: boolean;
    default_quantity: integer;
    current_stock_quantity: integer;
    preferred_stock_quantity: integer;
    min_stock_quantity: integer;
    low_stock_warning: boolean;
    low_stock_quantity: integer;
    is_active: boolean;
    comments: string;
    
};

export type ItemForm = {
    store_id: string | number;
    item_category_id: string | number;
    supplier_id: string | number;
    measure_unit_id: string | number;
    sku_code: string;
    name: string;
    description: string;
    is_service: boolean;
    in_stock: boolean;
    using_default_quantity: boolean;
    default_quantity: integer;
    current_stock_quantity: integer;
    preferred_stock_quantity: integer;
    min_stock_quantity: integer;
    low_stock_warning: boolean;
    low_stock_quantity: integer;
    is_active: boolean;
    comments: string;
};
