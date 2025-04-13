<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Semester;

class SemesterUniqueNameRule implements ValidationRule
{
    protected $id;
    protected $academicYear;

    public function __construct($id, $academicYear)
    {
        $this->id = $id;
        $this->academicYear = $academicYear;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = Semester::where('semester_name', $value)
            ->where('academic_year', $this->academicYear);

        if ($this->id) {
            $query->where('semester_id', '!=', $this->id);
        }

        if ($query->exists()) {
            $fail('Tên học kỳ đã tồn tại trong năm học này.');
        }
    }
}
