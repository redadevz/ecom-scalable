

export type InventoryCount = {
    id: string | number;
    store_id: string | number;
    physical_count_time: string;
    change_stock_time: string;
    description: string;
    comments: string;
    
};

export type InventoryCountForm = {
    store_id: string | number;
    physical_count_time: string;
    change_stock_time: string;
    description: string;
    comments: string;
};
