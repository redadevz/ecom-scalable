

export type Store = {
    id: string | number;
    city_id: string | number;
    language_id: string | number;
    currency_id: string | number;
    code: string;
    name: string;
    is_active: boolean;
    legal_entity_name: string;
    tax_code: string;
    address: string;
    registration_number: string;
    phone: string;
    fax: string;
    email: string;
    logo: string;
    bank_branch: string;
    bank_code: string;
    bank_account: string;
    
};

export type StoreForm = {
    city_id: string | number;
    language_id: string | number;
    currency_id: string | number;
    code: string;
    name: string;
    is_active: boolean;
    legal_entity_name: string;
    tax_code: string;
    address: string;
    registration_number: string;
    phone: string;
    fax: string;
    email: string;
    logo: string;
    bank_branch: string;
    bank_code: string;
    bank_account: string;
};
