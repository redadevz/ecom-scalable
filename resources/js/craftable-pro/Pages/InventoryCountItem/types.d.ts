

export type InventoryCountItem = {
    id: string | number;
    inventory_count_id: string | number;
    item_id: string | number;
    quantity_counted: integer;
    quantity_expected: integer;
    quantity_change: integer;
    description: string;
    comments: string;
    
};

export type InventoryCountItemForm = {
    inventory_count_id: string | number;
    item_id: string | number;
    quantity_counted: integer;
    quantity_expected: integer;
    quantity_change: integer;
    description: string;
    comments: string;
};
