<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Semester;
use Carbon\Carbon;

class SemesterOverlapRule implements ValidationRule
{
    protected $id;
    protected $endDate;

    public function __construct($id, $endDate)
    {
        $this->id = $id;
        $this->endDate = $endDate;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $startDate = Carbon::parse($value);
        $endDate = Carbon::parse($this->endDate);

        $overlaps = Semester::where(function ($query) use ($startDate, $endDate) {
            $query->where(function ($q) use ($startDate, $endDate) {
                $q->where('start_date', '<=', $endDate)
                    ->where('end_date', '>=', $startDate);
            });
            if ($this->id) {
                $query->where('semester_id', '!=', $this->id);
            }
        })->exists();

        if ($overlaps) {
            $fail('Thời gian học kỳ này trùng với một học kỳ đã tồn tại.');
        }
    }
}
