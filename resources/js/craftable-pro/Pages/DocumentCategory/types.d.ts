

export type DocumentCategory = {
    id: string | number;
    name: string;
    description: string;
    is_active: boolean;
    comments: string;
    
};

export type DocumentCategoryForm = {
    name: string;
    description: string;
    is_active: boolean;
    comments: string;
};
