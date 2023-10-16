<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductEditRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_name' => 'required|string|max:255',
            'product_address' => 'required|string|max:255',
            'product_phone' => 'required|string|max:16',
            'product_email' => 'required|email|max:255',
            'business_id' => 'required|exists:business,id',
            'product_description' => 'required|string',
            'product_status' => 'boolean',
            'product_service' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'product_name.required' => 'Vui lòng nhập tên sản phẩm.',
            'product_name.string' => 'Tên sản phẩm phải là chuỗi ký tự.',
            'product_name.max' => 'Tên sản phẩm không được vượt quá :max ký tự.',

            'product_address.required' => 'Vui lòng nhập địa chỉ sản phẩm.',
            'product_address.string' => 'Địa chỉ sản phẩm phải là chuỗi ký tự.',
            'product_address.max' => 'Địa chỉ sản phẩm không được vượt quá :max ký tự.',

            'product_phone.required' => 'Vui lòng nhập số điện thoại sản phẩm.',
            'product_phone.string' => 'Số điện thoại sản phẩm phải là chuỗi ký tự.',
            'product_phone.max' => 'Số điện thoại sản phẩm không được vượt quá :max ký tự.',
            'product_phone.unique' => 'Số điện thoại đã được sử dụng.',

            'product_email.required' => 'Vui lòng nhập địa chỉ email sản phẩm.',
            'product_email.email' => 'Địa chỉ email sản phẩm không hợp lệ.',
            'product_email.max' => 'Địa chỉ email sản phẩm không được vượt quá :max ký tự.',
            'product_email.unique' => 'Địa chỉ email đã được sử dụng.',

            'business_id.required' => 'Vui lòng chọn doanh nghiệp.',
            'business_id.exists' => 'Doanh nghiệp không tồn tại.',

            'product_description.required' => 'Vui lòng nhập mô tả sản phẩm.',
            'product_description.string' => 'Mô tả sản phẩm phải là chuỗi ký tự.',

            'product_status.boolean' => 'Trạng thái sản phẩm phải là kiểu boolean.',

            'product_service.required' => 'Vui lòng nhập dịch vụ sản phẩm.',
            'product_service.json' => 'Dịch vụ sản phẩm phải là chuỗi JSON hợp lệ.',
        ];
    }
}
