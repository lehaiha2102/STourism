<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_name' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s]+$/|unique:categories,category_name',
            'category_image' => 'required|image|mimes:jpeg,png|max:2048',
            'category_banner' => 'required|image|mimes:jpeg,png|max:2048',
            'category_status' => 'required|boolean',
            'category_description' => 'required|string',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'category_name' => 'Tên danh mục',
            'category_image' => 'Ảnh danh mục',
            'category_banner' => 'Ảnh banner danh mục',
            'category_status' => 'Trạng thái danh mục',
            'category_description' => 'Mô tả danh mục',
        ];
    }

    /**
     * Get custom messages for validator rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'category_name.required' => 'Tên danh mục là bắt buộc.',
            'category_name.string' => 'Tên danh mục phải là một chuỗi.',
            'category_name.max' => 'Tên danh mục không được vượt quá :max ký tự.',
            'category_name.regex' => 'Tên danh mục không được chứa ký tự đặc biệt.',
            'category_name.unique' => 'Tên danh mục đã tồn tại.',

            'category_image.required' => 'Ảnh danh mục là bắt buộc.',
            'category_image.image' => 'Trường này chỉ chấp nhận file hình ảnh.',
            'category_image.mimes' => 'Chỉ chấp nhận file ảnh định dạng JPEG hoặc PNG.',
            'category_image.max' => 'Kích thước tối đa của ảnh là 2MB.',

            'category_banner.required' => 'Ảnh banner danh mục là bắt buộc.',
            'category_banner.image' => 'Trường này chỉ chấp nhận file hình ảnh.',
            'category_banner.mimes' => 'Chỉ chấp nhận file ảnh định dạng JPEG hoặc PNG.',
            'category_banner.max' => 'Kích thước tối đa của ảnh là 2MB.',

            'category_status.required' => 'Trạng thái danh mục là bắt buộc.',
            'category_status.boolean' => 'Trạng thái danh mục phải là kiểu boolean.',

            'category_description.required' => 'Mô tả danh mục là bắt buộc.',
            'category_description.string' => 'Mô tả danh mục phải là một chuỗi.',
        ];
    }

}
