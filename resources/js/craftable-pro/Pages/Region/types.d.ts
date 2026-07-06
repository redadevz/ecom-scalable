

export type Region = {
    id: string | number;
    name: string;
    country_id: string | number;
    created_at?: string;
    country?: {
        id: string | number;
        name: string;
    } | null;
};

export type RegionForm = {
    name: string;
    country_id: string | number;
};
