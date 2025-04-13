<?php

namespace App\Http\Requests;

use App\Models\Contract;
use Illuminate\Foundation\Http\FormRequest;

class ContractRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        if (!$user) return false;

        // Sinh viên chỉ gửi hợp đồng, admin/staff cập nhật/xóa
        if ($this->isMethod('post')) {
            return $user->role === 'student';
        }
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => [
                'required',
                'exists:students,student_id',
                function ($attribute, $value, $fail) {
                    if (Contract::hasExistingContract($value, $this->input('semester_id'))) {
                        $fail('Sinh viên này đã có hợp đồng trong học kỳ này.');
                    }
                },
            ],
            'room_type_id' => 'required|exists:room_types,room_type_id',
            'room_id' => 'exists:rooms,room_id',
            'semester_id' => 'required|exists:semesters,semester_id',
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'Sinh viên không được để trống',
            'student_id.exists' => 'Sinh viên không tồn tại',
            'room_type_id.required' => 'Loại phòng không được để trống',
            'room_type_id.exists' => 'Loại phòng không tồn tại',
            'room_id.exists' => 'Phòng không tồn tại',
            'semester_id.required' => 'Học kỳ không được để trống',
            'semester_id.exists' => 'Học kỳ không tồn tại',
        ];
    }
}
