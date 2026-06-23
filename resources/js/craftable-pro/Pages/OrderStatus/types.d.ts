

export type OrderStatus = {
    id: string | number;
    name: string;
    description: string;
    is_default: boolean;
    
};

export type OrderStatusForm = {
    name: string;
    description: string;
    is_default: boolean;
};
