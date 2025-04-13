<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UtilityRateRequest extends FormRequest
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
            'electricity_rate' => 'required|numeric|min:1000',
            'water_rate' => 'required|numeric|min:1000',
            'effective_date' => 'required|date|unique:utility_rates,effective_date,' . ($this->route('rateId') ?? null) . ',rate_id',
        ];
    }

    public function messages(): array
    {
        return [
            'electricity_rate.required' => 'Giá điện không được để trống',
            'electricity_rate.numeric' => 'Giá điện phải là số',
            'electricity_rate.min' => 'Giá điện phải từ 1,000 trở lên',
            'water_rate.required' => 'Giá nước không được để trống',
            'water_rate.numeric' => 'Giá nước phải là số',
            'water_rate.min' => 'Giá nước phải từ 1,000 trở lên',
            'effective_date.required' => 'Ngày hiệu lực không được để trống',
            'effective_date.date' => 'Ngày hiệu lực phải là định dạng ngày hợp lệ',
            'effective_date.unique' => 'Ngày hiệu lực đã tồn tại',
        ];
    }
}
