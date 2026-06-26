

export type LossAndDamageItem = {
    id: string | number;
    loss_and_damage_id: string | number;
    item_id: string | number;
    quantity: integer;
    description: string;
    comments: string;
    
};

export type LossAndDamageItemForm = {
    loss_and_damage_id: string | number;
    item_id: string | number;
    quantity: integer;
    description: string;
    comments: string;
};
