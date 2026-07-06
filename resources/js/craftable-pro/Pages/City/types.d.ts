

export type City = {
    id: string | number;
    name: string;
    region_id: string | number;
    timezone_id: string | number;
    zipcode: integer;
    region?: { id: string | number; name: string } | null;
    created_at?: string;
};

export type CityForm = {
    name: string;
    region_id: string | number;
    timezone_id: string | number;
    zipcode: integer;
};
