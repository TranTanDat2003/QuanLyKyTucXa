<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StaffProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && in_array(Auth::user()->role, ['admin', 'staff']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $staffId = Auth::user()->staff->staff_id;
        return [
            'full_name' => 'required|string|max:50',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:0,1',
            'phone' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'email' => 'required|email|max:50|unique:staff,email,' . $staffId . ',staff_id',
            'image' => 'nullable|mimes:jpg,jpeg,png,webp,gif',
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Họ tên không được để trống',
            'full_name.max' => 'Họ tên không được vượt quá 50 ký tự',
            'date_of_birth.required' => 'Ngày sinh không được để trống',
            'date_of_birth.date' => 'Ngày sinh phải là định dạng ngày hợp lệ',
            'gender.required' => 'Giới tính không được để trống',
            'gender.in' => 'Giới tính phải là Nam hoặc Nữ',
            'phone.max' => 'Số điện thoại không được vượt quá 10 ký tự',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.max' => 'Email không được vượt quá 50 ký tự',
            'email.unique' => 'Email đã được sử dụng',
            'image.mimes' => 'Ảnh phải là JPG, JPEG, PNG, WEBP, hoặc GIF',
        ];
    }
}
