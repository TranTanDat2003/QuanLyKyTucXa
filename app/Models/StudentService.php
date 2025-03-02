<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentService extends Model
{
    protected $primaryKey = 'student_service_id';
    protected $fillable = ['student_id', 'service_id', 'start_date', 'end_date'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
