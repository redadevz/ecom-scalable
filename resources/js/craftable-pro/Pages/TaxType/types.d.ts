

export type TaxType = {
    id: string | number;
    store_id: string | number;
    name: string;
    code: string;
    description: string;
    is_percentage: boolean;
    value: decimal;
    start_time: string;
    end_time: string;
    is_active: boolean;
    comments: string;
    
};

export type TaxTypeForm = {
    store_id: string | number;
    name: string;
    code: string;
    description: string;
    is_percentage: boolean;
    value: decimal;
    start_time: string;
    end_time: string;
    is_active: boolean;
    comments: string;
};
