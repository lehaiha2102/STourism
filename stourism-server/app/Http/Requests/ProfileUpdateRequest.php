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
            'phone' => 'nullable|numeric',
            'avatar' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Add rules for 'avatar'
            'banner' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'dob.date' => 'Ngày sinh phải là một ngày hợp lệ.',
            'address.string' => 'Địa chỉ phải là một chuỗi.',
            'phone.numeric' => 'Số điện thoại phải là số.',
            'avatar.image' => 'Ảnh đại diện phải là một tệp hình ảnh.',
            'avatar.mimes' => 'Định dạng ảnh đại diện không được hỗ trợ. Vui lòng chọn ảnh có định dạng jpeg, png, jpg hoặc gif.',
            'avatar.max' => 'Kích thước ảnh đại diện không được vượt quá 2MB.',
            'banner.image' => 'Ảnh bìa phải là một tệp hình ảnh.',
            'banner.mimes' => 'Định dạng ảnh bìa không được hỗ trợ. Vui lòng chọn ảnh có định dạng jpeg, png, jpg hoặc gif.',
            'banner.max' => 'Kích thước ảnh bìa không được vượt quá 2MB.',
        ];
    }    
}
