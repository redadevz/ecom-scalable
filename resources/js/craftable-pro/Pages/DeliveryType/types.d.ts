

export type DeliveryType = {
    id: string | number;
    name: string;
    description: string;
    is_active: boolean;
    
};

export type DeliveryTypeForm = {
    name: string;
    description: string;
    is_active: boolean;
};
