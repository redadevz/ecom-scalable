

export type OrderStatusHistory = {
    id: string | number;
    order_id: string | number;
    order_status_id: string | number;
    start_time: string;
    end_time: string;
    created_at?: string;
    order?: {
        id: string | number;
        order_no: string;
    };
    order_status?: {
        id: string | number;
        name: string;
    };
};

export type OrderStatusHistoryForm = {
    order_id: string | number;
    order_status_id: string | number;
    start_time: string;
    end_time: string;
};
