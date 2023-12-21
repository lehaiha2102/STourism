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
            'product_name' => 'string|max:255',
            'product_address' => 'string|max:255',
            'product_phone' => 'numeric|digits:10',
            'product_email' => 'email|max:255',
            'business_id' => 'exists:business,id',
            'product_description' => 'string',
            'product_status' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'product_name.string' => 'Tên địa điểm nghỉ dưỡng phải là chuỗi ký tự.',
            'product_name.max' => 'Tên địa điểm nghỉ dưỡng không được vượt quá :max ký tự.',

            'product_address.string' => 'Địa chỉ địa điểm nghỉ dưỡng phải là chuỗi ký tự.',
            'product_address.max' => 'Địa chỉ địa điểm nghỉ dưỡng không được vượt quá :max ký tự.',

            'product_phone.numeric' => 'Số điện thoại khoogn đúng định dạng.',
            'product_phone.max' => 'Số điện thoại địa điểm nghỉ dưỡng không được vượt quá :max ký tự.',

            'product_email.email' => 'Địa chỉ email địa điểm nghỉ dưỡng không hợp lệ.',
            'product_email.max' => 'Địa chỉ email địa điểm nghỉ dưỡng không được vượt quá :max ký tự.',

            'business_id.exists' => 'Doanh nghiệp không tồn tại.',

            'product_description.string' => 'Mô tả địa điểm nghỉ dưỡng phải là chuỗi ký tự.',

            'product_status.boolean' => 'Trạng thái địa điểm nghỉ dưỡng phải là kiểu boolean.',

            'product_service.required' => 'Vui lòng nhập dịch vụ địa điểm nghỉ dưỡng.',
        ];
    }
}
