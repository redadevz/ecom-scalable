

export type ItemTaxType = {
    id: string | number;
    item_id: string | number;
    tax_type_id: string | number;
    start_time: string;
    end_time: string;
    description: string;
    
};

export type ItemTaxTypeForm = {
    item_id: string | number;
    tax_type_id: string | number;
    start_time: string;
    end_time: string;
    description: string;
};
