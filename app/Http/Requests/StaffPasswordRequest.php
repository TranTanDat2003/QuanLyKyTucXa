<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StaffPasswordRequest extends FormRequest
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
        return [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Mật khẩu hiện tại không được để trống',
            'new_password.required' => 'Mật khẩu mới không được để trống',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự',
            'new_password.confirmed' => 'Mật khẩu mới không khớp với xác nhận',
            'new_password_confirmation.required' => 'Xác nhận mật khẩu mới không được để trống',
        ];
    }
}
