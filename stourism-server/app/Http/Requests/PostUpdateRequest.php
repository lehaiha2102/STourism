<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'target' => 'required|exists:products,id',
            'description' => 'required|string',
            'content' => 'required|string',
            'images' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Trường tiêu đề là bắt buộc.',
            'title.string' => 'Trường tiêu đề phải là chuỗi.',
            'title.max' => 'Tiêu đề không được vượt quá :max ký tự.',
            
            'target.required' => 'Trường đối tượng là bắt buộc.',
            'target.exists' => 'Đối tượng đã chọn không tồn tại.',
            
            'description.required' => 'Trường mô tả là bắt buộc.',
            'description.string' => 'Trường mô tả phải là chuỗi.',
            
            'content.required' => 'Trường nội dung là bắt buộc.',
            'content.string' => 'Trường nội dung phải là chuỗi.',
            
            'images.image' => 'Trường hình ảnh phải là một hình ảnh.',
            'images.mimes' => 'Hình ảnh phải có định dạng: :mimes.',
            'images.max' => 'Kích thước hình ảnh không được vượt quá :max kilobytes.',
        ];
    }
}
