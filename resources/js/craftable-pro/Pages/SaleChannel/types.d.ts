

export type SaleChannel = {
    id: string | number;
    name: string;
    description: string;
    is_active: boolean;
    
};

export type SaleChannelForm = {
    name: string;
    description: string;
    is_active: boolean;
};
