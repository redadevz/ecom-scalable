

export type InventoryCountItem = {
    id: string | number;
    inventory_count_id: string | number;
    item_id: string | number;
    quantity_counted: integer;
    quantity_expected: integer;
    quantity_change: integer;
    description: string;
    comments: string;
    created_at?: string;
    item?: { id: string | number; name: string } | null;
    inventory_count?: { id: string | number } | null;
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
