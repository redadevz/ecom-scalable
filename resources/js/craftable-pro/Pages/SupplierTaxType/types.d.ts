

export type SupplierTaxType = {
    id: string | number;
    supplier_id: string | number;
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

export type SupplierTaxTypeForm = {
    supplier_id: string | number;
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
