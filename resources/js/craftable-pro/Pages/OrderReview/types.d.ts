

export type OrderReview = {
    id: string | number;
    order_id: string | number;
    customer_id: string | number;
    replied_by: string | number;
    rating: boolean;
    review: string;
    review_time: string;
    reply: string;
    reply_time: string;
    is_compensated: boolean;
    compensation_value: string;
    comments: string;
    
};

export type OrderReviewForm = {
    order_id: string | number;
    customer_id: string | number;
    replied_by: string | number;
    rating: boolean;
    review: string;
    review_time: string;
    reply: string;
    reply_time: string;
    is_compensated: boolean;
    compensation_value: string;
    comments: string;
};
