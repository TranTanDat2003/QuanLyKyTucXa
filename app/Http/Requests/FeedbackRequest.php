<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeedbackRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        if (!$user) return false;

        // Sinh viên chỉ gửi feedback, admin/staff cập nhật/xóa
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
        if ($this->isMethod('post')) {
            // Rules cho tạo mới (store)
            return [
                'content' => 'required|string|max:1000',
                'image' => 'nullable|mimes:jpg,jpeg,png,gif|max:2048',
                'scheduled_fix_date' => 'nullable|date|after_or_equal:today',
                'quantity' => 'required|integer|min:1|max:255',
                'status' => 'sometimes|in:pending,approved,rejected',
                'room_id' => 'required|exists:rooms,room_id',
                'student_id' => 'required|exists:students,student_id',
                'staff_id' => 'nullable|exists:staff,staff_id',
            ];
        }

        // Rules cho cập nhật (update)
        return [
            'status' => 'required|in:pending,approved,rejected',
            'scheduled_fix_date' => 'nullable|date|after_or_equal:today',
            'staff_id' => 'nullable|exists:staff,staff_id',
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Nội dung báo cáo không được để trống',
            'content.max' => 'Nội dung không được vượt quá 1000 ký tự',
            'image.mimes' => 'Ảnh phải có định dạng JPG, JPEG, PNG, hoặc GIF',
            'image.max' => 'Ảnh không được lớn hơn 2MB',
            'scheduled_fix_date.date' => 'Ngày hẹn sửa chữa phải là ngày hợp lệ',
            'scheduled_fix_date.after_or_equal' => 'Ngày hẹn sửa chữa phải từ hôm nay trở đi',
            'quantity.required' => 'Số lượng lỗi không được để trống',
            'quantity.integer' => 'Số lượng lỗi phải là số nguyên',
            'quantity.min' => 'Số lượng lỗi phải từ 1 trở lên',
            'quantity.max' => 'Số lượng lỗi không được vượt quá 255',
            'status.in' => 'Trạng thái không hợp lệ',
            'room_id.required' => 'Phòng không được để trống',
            'room_id.exists' => 'Phòng không tồn tại',
            'student_id.required' => 'Sinh viên không được để trống',
            'student_id.exists' => 'Sinh viên không tồn tại',
            'staff_id.exists' => 'Nhân viên không tồn tại',
        ];
    }
}
