

export type Customer = {
    id: string | number;
    city_id: string | number;
    created_at_store_id: string | number;
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
    is_registered_online: boolean;
    email: string;
    username: string;
    password: string;
    credit: decimal;
    last_login_time: string;
    is_active: boolean;
    comments: string;
    
};

export type CustomerForm = {
    city_id: string | number;
    created_at_store_id: string | number;
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
    is_registered_online: boolean;
    email: string;
    username: string;
    password: string;
    credit: decimal;
    last_login_time: string;
    is_active: boolean;
    comments: string;
};
