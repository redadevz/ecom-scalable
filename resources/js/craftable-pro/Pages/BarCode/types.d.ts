

export type BarCode = {
    id: string | number;
    item_id: string | number;
    bar_code: string;
    is_active: boolean;
    description: string;
    
};

export type BarCodeForm = {
    item_id: string | number;
    bar_code: string;
    is_active: boolean;
    description: string;
};
