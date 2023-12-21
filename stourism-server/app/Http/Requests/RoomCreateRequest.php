<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'room_name' => 'required|string|max:255|unique:rooms,room_name',
            'product_id' => 'required|exists:products,id',
            'room_image' => 'required',
            'room_quantity' => 'integer|min:1',
            'adult_capacity' => 'integer|min:1',
            'children_capacity' => 'integer|min:0',
            'room_rental_price' => 'required|numeric|min:0',
            'room_description' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'room_name' => 'Tên phòng',
            'room_slug' => 'Slug phòng',
            'product_id' => 'ID sản phẩm',
            'room_image' => 'Hình ảnh phòng',
            'room_quantity' => 'Số lượng phòng',
            'adult_capacity' => 'Sức chứa người lớn',
            'children_capacity' => 'Sức chứa trẻ em',
            'room_rental_price' => 'Giá thuê phòng',
            'room_description' => 'Mô tả phòng',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Trường :attribute là bắt buộc.',
            'numeric' => 'Trường :attribute phải là một số.',
            'integer' => 'Trường :attribute phải là một số nguyên.',
            'min' => 'Trường :attribute phải có giá trị tối thiểu là :min.',
        ];
    }
}
