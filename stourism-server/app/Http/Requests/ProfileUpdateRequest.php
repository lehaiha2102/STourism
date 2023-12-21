<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'dob' => 'date',
            'address' => 'string',
            'phone' => 'nullable|numeric', // Chỉ nếu muốn kiểm tra số điện thoại nếu có
        ];
    }

    public function messages()
    {
        return [
            'dob.date' => 'Ngày sinh phải là một ngày hợp lệ.',
            'address.string' => 'Địa chỉ phải là một chuỗi.',
            'phone.numeric' => 'Số điện thoại phải là số.',
        ];
    }
}
