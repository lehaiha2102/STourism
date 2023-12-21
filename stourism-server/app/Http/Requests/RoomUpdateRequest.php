<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'room_name' => 'string|max:255|unique:rooms,room_name',
            'product_id' => 'exists:products,id',
            'room_quantity' => 'integer|min:1',
            'adult_capacity' => 'integer|min:1',
            'children_capacity' => 'integer|min:0',
            'room_rental_price' => 'numeric|min:100000',
            'room_description' => 'string',
        ];
    }

    public function attributes()
    {
        return [
            'room_name' => 'Tên phòng',
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
            'string' => 'Trường :attribute phải là chuỗi.',
            'max' => 'Trường :attribute không được vượt quá :max ký tự.',
            'exists' => 'Trường :attribute không tồn tại.',
            'integer' => 'Trường :attribute phải là một số nguyên.',
            'min' => 'Trường :attribute phải có giá trị tối thiểu là :min.',
            'numeric' => 'Trường :attribute phải là một số.',
            'unique' => 'Trường :attribute đã tồn tại'
        ];
    }
}
