

export type DiscountSchedule = {
    id: string | number;
    discount_type_id: string | number;
    day_of_week: boolean;
    start_time: time;
    end_time: time;
    comments: string;
    
};

export type DiscountScheduleForm = {
    discount_type_id: string | number;
    day_of_week: boolean;
    start_time: time;
    end_time: time;
    comments: string;
};
