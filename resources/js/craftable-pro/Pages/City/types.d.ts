

export type City = {
    id: string | number;
    name: string;
    region_id: string | number;
    timezone_id: string | number;
    zipcode: integer;
    
};

export type CityForm = {
    name: string;
    region_id: string | number;
    timezone_id: string | number;
    zipcode: integer;
};
