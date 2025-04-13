<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->role === 'student';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:50',
            'date_of_birth' => 'required|date',
            'phone' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:students,email,' . Auth::user()->student->student_id . ',student_id',
            'major' => 'nullable|string|max:100',
            'class' => 'nullable|string|max:50',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Họ và tên không được để trống',
            'full_name.max' => 'Họ và tên không được vượt quá 50 ký tự',
            'date_of_birth.required' => 'Ngày sinh không được để trống',
            'date_of_birth.date' => 'Ngày sinh phải là định dạng ngày hợp lệ',
            'phone.max' => 'Số điện thoại không được vượt quá 10 ký tự',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.max' => 'Email không được vượt quá 255 ký tự',
            'email.unique' => 'Email đã được sử dụng',
            'major.max' => 'Ngành học không được vượt quá 100 ký tự',
            'class.max' => 'Lớp không được vượt quá 50 ký tự',
            'image.mimes' => 'Ảnh phải có định dạng JPG, JPEG, PNG, hoặc GIF',
            'image.max' => 'Ảnh không được lớn hơn 2MB',
        ];
    }
}
