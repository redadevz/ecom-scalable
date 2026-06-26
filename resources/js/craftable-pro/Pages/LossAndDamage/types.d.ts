

export type LossAndDamage = {
    id: string | number;
    store_id: string | number;
    exit_stock_time: string;
    description: string;
    comments: string;
    
};

export type LossAndDamageForm = {
    store_id: string | number;
    exit_stock_time: string;
    description: string;
    comments: string;
};
