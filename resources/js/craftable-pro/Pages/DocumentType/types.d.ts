

export type DocumentType = {
    id: string | number;
    document_category_id: string | number;
    name: string;
    description: string;
    is_active: boolean;
    comments: string;
    
};

export type DocumentTypeForm = {
    document_category_id: string | number;
    name: string;
    description: string;
    is_active: boolean;
    comments: string;
};
