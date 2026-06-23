

export type SupplierItemTaxType = {
    id: string | number;
    item_id: string | number;
    supplier_tax_type_id: string | number;
    start_time: string;
    end_time: string;
    description: string;
    
};

export type SupplierItemTaxTypeForm = {
    item_id: string | number;
    supplier_tax_type_id: string | number;
    start_time: string;
    end_time: string;
    description: string;
};
