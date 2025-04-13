<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $table = 'semesters';
    protected $primaryKey = 'semester_id';

    protected $fillable = [
        'semester_name',
        'academic_year',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => 'boolean',
    ];

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'semester_id');
    }

    public function serviceBills()
    {
        return $this->hasMany(ServiceBill::class, 'semester_id');
    }

    // Lấy học kỳ hiện tại
    public static function getCurrentSemester()
    {
        return self::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('status', 1)
            ->first();
    }

    // Lấy học kỳ tiếp theo
    public static function getNextSemester()
    {
        return self::where('status', 0)
            ->where('start_date', '>', now())
            ->orderBy('start_date', 'asc')
            ->first();
    }
}
