<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
        $id = $this->route('studentId') ?? $this->input('student_id');
        return [
            'full_name' => 'required|string|max:100',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:0,1',
            'phone' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:students,email,' . ($id ?? null) . ',student_id',
            'major' => 'nullable|string|max:100',
            'class' => 'nullable|string|max:50',
            'enrollment_year' => 'required|integer|min:2000|max:' . date('Y'),
            'image' => 'nullable|mimes:jpg,jpeg,png,webp,gif' . ($id ? '|sometimes' : ''),
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Họ tên không được để trống',
            'full_name.max' => 'Họ tên không được vượt quá 100 ký tự',
            'date_of_birth.required' => 'Ngày sinh không được để trống',
            'date_of_birth.date' => 'Ngày sinh phải là định dạng ngày hợp lệ',
            'gender.required' => 'Giới tính không được để trống',
            'gender.in' => 'Giới tính phải là Nam hoặc Nữ',
            'phone.max' => 'Số điện thoại không được vượt quá 10 ký tự',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.max' => 'Email không được vượt quá 255 ký tự',
            'email.unique' => 'Email đã được sử dụng',
            'major.max' => 'Ngành học không được vượt quá 100 ký tự',
            'class.max' => 'Lớp không được vượt quá 50 ký tự',
            'enrollment_year.required' => 'Năm nhập học không được để trống',
            'enrollment_year.integer' => 'Năm nhập học phải là số nguyên',
            'enrollment_year.min' => 'Năm nhập học phải từ 2000 trở lên',
            'enrollment_year.max' => 'Năm nhập học không được vượt quá năm hiện tại',
            'image.mimes' => 'Ảnh phải là JPG, JPEG, PNG, WEBP, hoặc GIF',
        ];
    }
}
