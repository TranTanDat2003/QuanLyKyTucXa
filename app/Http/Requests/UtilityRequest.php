<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UtilityRequest extends FormRequest
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
        $id = $this->route('utilityId') ?? $this->input('utility_id');
        return [
            'month' => 'required|date|unique:utilities,month,' . ($id ?? null) . ',utility_id,room_id,' . $this->input('room_id'),
            'electricity_reading' => 'required|numeric|min:0',
            'water_reading' => 'required|numeric|min:0',
            'room_id' => 'required|exists:rooms,room_id',
            'rate_id' => 'required|exists:utility_rates,rate_id',
        ];
    }

    public function messages(): array
    {
        return [
            'month.required' => 'Tháng không được để trống',
            'month.date' => 'Tháng phải là định dạng ngày hợp lệ',
            'month.unique' => 'Tiện ích cho phòng này trong tháng đã tồn tại',
            'electricity_reading.required' => 'Chỉ số điện không được để trống',
            'electricity_reading.numeric' => 'Chỉ số điện phải là số',
            'electricity_reading.min' => 'Chỉ số điện không được nhỏ hơn 0',
            'water_reading.required' => 'Chỉ số nước không được để trống',
            'water_reading.numeric' => 'Chỉ số nước phải là số',
            'water_reading.min' => 'Chỉ số nước không được nhỏ hơn 0',
            'room_id.required' => 'Phòng không được để trống',
            'room_id.exists' => 'Phòng không tồn tại',
            'rate_id.required' => 'Biểu giá không được để trống',
            'rate_id.exists' => 'Biểu giá không tồn tại',
        ];
    }
}
