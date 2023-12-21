<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusinessUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'business_name' => 'required|string|max:255',
            'business_logo' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Giả sử business_logo là hình ảnh
            'business_banner' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Giả sử business_banner là hình ảnh
            'business_email' => 'required|email',
            'business_phone' => 'required|string',
            'business_address' => 'required|string',
            'business_segment' => 'array',
            'business_segment.*' => 'string|max:255',
            'user_id' => 'required|exists:users,id',
            'business_status' => 'boolean',
        ];
    }

    public function attributes()
    {
        return [
            'business_name' => 'Tên doanh nghiệp',
            'business_logo' => 'Logo doanh nghiệp',
            'business_banner' => 'Banner doanh nghiệp',
            'business_email' => 'Email doanh nghiệp',
            'business_phone' => 'Điện thoại doanh nghiệp',
            'business_address' => 'Địa chỉ doanh nghiệp',
            'business_segment' => 'Lĩnh vực doanh nghiệp',
            'user_id' => 'Người dùng',
            'business_status' => 'Trạng thái doanh nghiệp',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Trường :attribute là bắt buộc.',
            'string' => 'Trường :attribute phải là chuỗi.',
            'max' => 'Trường :attribute không được vượt quá :max ký tự.',
            'image' => 'Trường :attribute phải là hình ảnh.',
            'mimes' => 'Trường :attribute phải là một tệp có loại: :values.',
            'email' => 'Trường :attribute phải là địa chỉ email hợp lệ.',
            'array' => 'Trường :attribute phải là một mảng.',
            'exists' => 'Giá trị đã chọn của :attribute không hợp lệ.',
            'boolean' => 'Trường :attribute phải là true hoặc false.',
        ];
    }
}
