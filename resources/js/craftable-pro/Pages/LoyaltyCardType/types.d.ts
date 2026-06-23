

export type LoyaltyCardType = {
    id: string | number;
    name: string;
    description: string;
    is_active: boolean;
    
};

export type LoyaltyCardTypeForm = {
    name: string;
    description: string;
    is_active: boolean;
};
