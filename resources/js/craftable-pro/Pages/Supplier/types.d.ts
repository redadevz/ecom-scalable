

export type Supplier = {
    id: string | number;
    store_id: string | number;
    city_id: string | number;
    created_by: string | number;
    code: string;
    phone: string;
    first_name: string;
    last_name: string;
    is_company: boolean;
    company_name: string;
    tax_number: string;
    is_tax_exempted: boolean;
    billing_address: string;
    postal_code: string;
    email: string;
    is_active: boolean;
    comments: string;
    
};

export type SupplierForm = {
    store_id: string | number;
    city_id: string | number;
    created_by: string | number;
    code: string;
    phone: string;
    first_name: string;
    last_name: string;
    is_company: boolean;
    company_name: string;
    tax_number: string;
    is_tax_exempted: boolean;
    billing_address: string;
    postal_code: string;
    email: string;
    is_active: boolean;
    comments: string;
};
