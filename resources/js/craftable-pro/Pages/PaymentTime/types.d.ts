

export type PaymentTime = {
    id: string | number;
    name: string;
    description: string;
    is_active: boolean;
    
};

export type PaymentTimeForm = {
    name: string;
    description: string;
    is_active: boolean;
};
