<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';
    protected $primaryKey = 'student_id';

    protected $fillable = [
        'student_code',
        'full_name',
        'date_of_birth',
        'gender',
        'phone',
        'address',
        'email',
        'major',
        'class',
        'enrollment_year',
        'image',
        'user_id',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'student_id');
    }

    public function serviceBills()
    {
        return $this->hasMany(ServiceBill::class, 'student_id');
    }

    // Lấy danh sách dịch vụ qua service_bills
    public function serviceBillItems()
    {
        return $this->hasManyThrough(ServiceBillItem::class, ServiceBill::class, 'student_id', 'service_bill_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'student_id');
    }

    // Accessor: Định dạng ngày sinh
    public function getDateOfBirthAttribute($value)
    {
        return $value ? $this->asDateTime($value)->format('d/m/Y') : null;
    }

    public static function generateStudentCode($enrollmentYear)
    {
        $year = substr($enrollmentYear, -2);

        $lastStudentCode = self::where('student_code', 'like', "B{$year}%")->max('student_code');

        $nextNumber = $lastStudentCode ? (int)substr($lastStudentCode, 3) + 1 : 1;

        return sprintf("B%s%05d", $year, $nextNumber);
    }

    public static function getGenderById($studentId)
    {
        return self::where('student_id', $studentId)->value('gender');
    }
}
