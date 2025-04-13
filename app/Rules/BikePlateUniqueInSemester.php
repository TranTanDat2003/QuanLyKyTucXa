<?php

namespace App\Rules;

use App\Models\Semester;
use App\Models\ServiceBillItem;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class BikePlateUniqueInSemester implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $semester = Semester::getCurrentSemester() ?? Semester::getNextSemester();
        $semesterId = $semester ? $semester->semester_id : null;
        $studentId = Auth::user()->student->student_id;

        $isDuplicate = ServiceBillItem::where('bike_plate', $value)
            ->whereIn('service_bill_id', function ($query) use ($studentId, $semesterId) {
                $query->select('service_bill_id')
                    ->from('service_bills')
                    ->where('student_id', $studentId)
                    ->where('semester_id', $semesterId)
                    ->where('status', 'pending');
            })
            ->exists();

        if ($isDuplicate) {
            $fail('Biển số xe này đã được đăng ký trong học kỳ hiện tại.');
        }
    }
}
