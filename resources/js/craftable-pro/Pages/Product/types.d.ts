

export type Product = {
    id: string | number;
    name: string;
    slug: string;
    description: string;
    price: decimal;
    stock: integer;
    category: string;
    image_url: string;
    
};

export type ProductForm = {
    name: string;
    slug: string;
    description: string;
    price: decimal;
    stock: integer;
    category: string;
    image_url: string;
};
