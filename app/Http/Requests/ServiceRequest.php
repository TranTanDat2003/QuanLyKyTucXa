<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
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
        $id = $this->route('serviceId') ?? $this->input('service_id');
        return [
            'service_name' => 'required|string|max:50|unique:services,service_name,' . ($id ?? null) . ',service_id',
            'price' => 'required|numeric|min:1000',
            'is_active' => 'boolean',
            'service_img' => 'sometimes|required|mimes:jpg,jpeg,png,webp,gif',
            'service_description' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'service_name.required' => 'Tên dịch vụ không được để trống',
            'service_name.unique' => 'Tên dịch vụ đã tồn tại',
            'service_name.max' => 'Tên dịch vụ không được quá 50 ký tự',
            'price.required' => 'Giá dịch vụ không được để trống',
            'price.numeric' => 'Giá dịch vụ phải là số',
            'price.min' => 'Giá dịch vụ phải từ 1,000 trở lên',
            'is_active.boolean' => 'Trạng thái phải là true hoặc false',
            'service_img.required' => 'Ảnh dịch vụ không được để trống',
            'service_img.mimes' => 'Chỉ cho phép JPG, JPEG, PNG, WEBP, GIF',
            'service_description.max' => 'Mô tả dịch vụ không được quá 500 ký tự',
        ];
    }
}
