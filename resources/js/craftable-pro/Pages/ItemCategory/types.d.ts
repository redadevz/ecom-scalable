

export type ItemCategory = {
    id: string | number;
    parent_category_id: string | number;
    name: string;
    description: string;
    is_active: boolean;
    
};

export type ItemCategoryForm = {
    parent_category_id: string | number;
    name: string;
    description: string;
    is_active: boolean;
};
