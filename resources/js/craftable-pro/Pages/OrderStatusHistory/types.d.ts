

export type OrderStatusHistory = {
    id: string | number;
    order_id: string | number;
    order_status_id: string | number;
    start_time: string;
    end_time: string;
    
};

export type OrderStatusHistoryForm = {
    order_id: string | number;
    order_status_id: string | number;
    start_time: string;
    end_time: string;
};
