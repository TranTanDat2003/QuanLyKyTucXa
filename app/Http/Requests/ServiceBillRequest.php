<?php

namespace App\Http\Requests;

use App\Models\Semester;
use App\Models\Service;
use App\Rules\BikePlateRequiredIfBikeService;
use App\Rules\BikePlateUniqueInSemester;
use Illuminate\Foundation\Http\FormRequest;

class ServiceBillRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'student';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'service_id' => 'required|exists:services,service_id',
            'bike_plate' => [
                'nullable',
                new BikePlateRequiredIfBikeService($this->service_id),
                'regex:/^[0-9]{2}[A-Z][0-9]{6}$/',
                new BikePlateUniqueInSemester(),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'service_id.required' => 'Dịch vụ không được để trống.',
            'service_id.exists' => 'Dịch vụ không tồn tại.',
            'bike_plate.regex' => 'Biển số xe phải có định dạng như 65B152172 (2 số, 1 chữ cái, 6 số, viết liền).',
        ];
    }
}
