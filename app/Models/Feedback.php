<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $primaryKey = 'feedback_id';
    protected $table = 'feedbacks';

    protected $fillable = [
        'content',
        'image',
        'scheduled_fix_date',
        'quantity',
        'status',
        'room_id',
        'student_id',
        'staff_id',
    ];

    protected $casts = [
        'scheduled_fix_date' => 'date',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public static function getFeedbacksByStudent($studentId)
    {
        return self::where('student_id', $studentId)->get();
    }
}
