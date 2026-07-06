

export type LossAndDamageItem = {
    id: string | number;
    loss_and_damage_id: string | number;
    item_id: string | number;
    quantity: integer;
    description: string;
    comments: string;
    created_at?: string;
    item?: { id: string | number; name: string } | null;
    loss_and_damage?: { id: string | number } | null;
};

export type LossAndDamageItemForm = {
    loss_and_damage_id: string | number;
    item_id: string | number;
    quantity: integer;
    description: string;
    comments: string;
};
