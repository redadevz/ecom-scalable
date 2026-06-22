

export type Order = {
    id: string | number;
    user_id: string | number;
    status: string;
    total: decimal;
    
};

export type OrderForm = {
    user_id: string | number;
    status: string;
    total: decimal;
};
