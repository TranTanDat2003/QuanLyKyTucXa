<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUtilityRequest extends FormRequest
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
        return [
            'room_id' => 'required|exists:rooms,room_id',
            'month' => 'required|date',
            'electricity_usage' => 'required|numeric|min:0',
            'water_usage' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'room_id.required' => 'Vui lòng chọn phòng.',
            'room_id.exists' => 'Phòng không tồn tại.',
            'month.required' => 'Vui lòng nhập tháng.',
            'month.date' => 'Tháng phải là định dạng ngày hợp lệ.',
            'electricity_usage.required' => 'Vui lòng nhập số điện.',
            'electricity_usage.numeric' => 'Số điện phải là số.',
            'electricity_usage.min' => 'Số điện không được nhỏ hơn 0.',
            'water_usage.required' => 'Vui lòng nhập số nước.',
            'water_usage.numeric' => 'Số nước phải là số.',
            'water_usage.min' => 'Số nước không được nhỏ hơn 0.',
        ];
    }
}
