<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $primaryKey = 'semester_id';
    protected $fillable = ['semester_name', 'start_date', 'end_date'];

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'semester_id');
    }

    // Lấy học kỳ hiện tại
    public static function getCurrentSemester()
    {
        return self::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->firstOrFail();
    }
}
