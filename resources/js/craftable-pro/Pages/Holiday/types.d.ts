

export type Holiday = {
    id: string | number;
    store_id: string | number;
    name: string;
    reason: string;
    starts_at: string;
    ends_at: string;
    comments: string;
    
};

export type HolidayForm = {
    store_id: string | number;
    name: string;
    reason: string;
    starts_at: string;
    ends_at: string;
    comments: string;
};
