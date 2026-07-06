

export type LossAndDamage = {
    id: string | number;
    store_id: string | number;
    exit_stock_time: string | null;
    description: string;
    comments: string;
    created_at: string;
    store?: {
        id: string | number;
        name: string;
    } | null;
};

export type LossAndDamageForm = {
    store_id: string | number;
    exit_stock_time: string;
    description: string;
    comments: string;
};
