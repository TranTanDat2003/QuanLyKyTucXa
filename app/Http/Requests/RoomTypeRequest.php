<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && in_array($this->user()->role, ['admin', 'staff']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('roomTypeId') ?? $this->input('room_type_id');
        return [
            'room_type_name' => 'required|string|max:50|unique:room_types,room_type_name,' . ($id ?? null) . ',room_type_id',
            'capacity' => 'required|integer|min:2|max:8',
            'has_air_conditioner' => 'boolean',
            'allow_cooking' => 'boolean',
            'room_type_price' => 'required|numeric|min:100000',
            'room_type_img' => 'required|mimes:jpg,jpeg,png,webp,gif' . ($id ? '|sometimes' : ''),
        ];
    }

    public function messages(): array
    {
        return [
            'room_type_name.required' => 'Tên loại phòng không được để trống',
            'room_type_name.unique' => 'Tên loại phòng đã được sử dụng',
            'room_type_name.max' => 'Tên loại phòng không được quá 50 ký tự',
            'capacity.required' => 'Số lượng người không được để trống',
            'capacity.integer' => 'Số lượng người phải là số nguyên',
            'capacity.min' => 'Số lượng người phải từ 2 trở lên',
            'capacity.max' => 'Số lượng người phải nhỏ hơn hoặc bằng 8',
            'has_air_conditioner.boolean' => 'Trường này phải là true hoặc false',
            'allow_cooking.boolean' => 'Trường này phải là true hoặc false',
            'room_type_price.required' => 'Giá loại phòng không được để trống',
            'room_type_price.numeric' => 'Giá loại phòng phải là số',
            'room_type_price.min' => 'Giá loại phòng phải từ 100,000',
            'room_type_img.required' => 'Ảnh loại phòng không được để trống',
            'room_type_img.mimes' => 'Chỉ cho phép JPG, JPEG, PNG, WEBP, GIF',
        ];
    }
}
