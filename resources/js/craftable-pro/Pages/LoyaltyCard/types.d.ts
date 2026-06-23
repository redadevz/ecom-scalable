

export type LoyaltyCard = {
    id: string | number;
    loyalty_card_type_id: string | number;
    customer_id: string | number;
    code: string;
    is_active: boolean;
    
};

export type LoyaltyCardForm = {
    loyalty_card_type_id: string | number;
    customer_id: string | number;
    code: string;
    is_active: boolean;
};
