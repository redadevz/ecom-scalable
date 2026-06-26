

export type SaleReturnItem = {
    id: string | number;
    sale_return_id: string | number;
    order_line_id: string | number;
    item_id: string | number;
    line_no: string;
    quantity: integer;
    return_quantity: integer;
    description: string;
    comments: string;
    
};

export type SaleReturnItemForm = {
    sale_return_id: string | number;
    order_line_id: string | number;
    item_id: string | number;
    line_no: string;
    quantity: integer;
    return_quantity: integer;
    description: string;
    comments: string;
};
