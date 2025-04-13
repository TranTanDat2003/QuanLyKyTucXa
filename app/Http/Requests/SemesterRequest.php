<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\SemesterOverlapRule;
use App\Rules\SemesterUniqueNameRule;

class SemesterRequest extends FormRequest
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
        $id = $this->route('semesterId') ?? $this->input('semester_id');

        return [
            'semester_name' => [
                'required',
                'string',
                'max:50',
                'semester_name_unique' => new SemesterUniqueNameRule($id, $this->input('academic_year')),
            ],
            'academic_year' => 'required|string|max:50',
            'start_date' => [
                'required',
                'date',
                'before:end_date',
                'semester_overlap' => new SemesterOverlapRule($id, $this->input('end_date')),
            ],
            'end_date' => 'required|date|after:start_date',
        ];
    }

    public function messages(): array
    {
        return [
            'semester_name.required' => 'Tên học kỳ không được để trống',
            'semester_name.max' => 'Tên học kỳ không được quá 50 ký tự',
            'semester_name.semester_name_unique' => 'Tên học kỳ đã tồn tại trong năm học này',
            'academic_year.string' => 'Năm học phải là chuỗi ký tự',
            'academic_year.required' => 'Năm học không được để trống',
            'academic_year.max' => 'Năm học không được quá 50 ký tự',
            'start_date.required' => 'Ngày bắt đầu không được để trống',
            'start_date.date' => 'Ngày bắt đầu phải là định dạng ngày hợp lệ',
            'start_date.before' => 'Ngày bắt đầu phải trước ngày kết thúc',
            'start_date.semester_overlap' => 'Khoảng thời gian này đã có học kỳ khác',
            'end_date.required' => 'Ngày kết thúc không được để trống',
            'end_date.date' => 'Ngày kết thúc phải là định dạng ngày hợp lệ',
            'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu',
        ];
    }
}
