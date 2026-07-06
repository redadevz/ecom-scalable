

export type Holiday = {
    id: string | number;
    store_id: string | number;
    name: string;
    reason: string;
    starts_at: string;
    ends_at: string;
    comments: string;
    created_at?: string;
    store?: { id: string | number; name: string };
};

export type HolidayForm = {
    store_id: string | number;
    name: string;
    reason: string;
    starts_at: string;
    ends_at: string;
    comments: string;
};
