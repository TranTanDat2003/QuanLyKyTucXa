<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuildingRequest extends FormRequest
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
        $id = $this->route('buildingId') ?? $this->input('building_id');
        return [
            'building_name' => 'required|string|max:50|unique:buildings,building_name,' . ($id ?? null) . ',building_id',
            'description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'building_name.required' => 'Tên tòa nhà không được để trống',
            'building_name.string' => 'Tên tòa nhà phải là chuỗi ký tự',
            'building_name.max' => 'Tên tòa nhà không được quá 50 ký tự',
            'building_name.unique' => 'Tên tòa nhà đã được sử dụng',
            'description.string' => 'Mô tả phải là chuỗi ký tự',
        ];
    }
}
