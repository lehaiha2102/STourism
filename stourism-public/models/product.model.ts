interface IProduct {
    id?: number;
    product_name?: string;
    product_slug?: string;
    product_address?: string;
    product_phone?: string;
    product_email?: string;
    product_main_image?: string;
    product_image?: string[];
    business_id?: number;
    product_description?: string;
    product_status?: boolean;
    product_service?: string[];
    ward_id?: number;
    created_at?: string;
    updated_at?: string;
}

export default IProduct;