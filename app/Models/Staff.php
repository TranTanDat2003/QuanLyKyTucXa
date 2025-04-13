<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';
    protected $primaryKey = 'staff_id';

    protected $fillable = [
        'staff_code',
        'full_name',
        'date_of_birth',
        'gender',
        'phone',
        'address',
        'email',
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
        return $this->hasMany(Contract::class, 'staff_id');
    }

    public function createdUtilities()
    {
        return $this->hasMany(Utility::class, 'created_by');
    }

    public function updatedUtilities()
    {
        return $this->hasMany(Utility::class, 'updated_by');
    }

    // Accessor: Định dạng ngày sinh
    public function getDateOfBirthAttribute($value)
    {
        return $value ? $this->asDateTime($value)->format('d/m/Y') : null;
    }

    public static function generateStaffCode()
    {
        $yearPrefix = "00" . substr(date('Y'), -2);

        $lastStaffCode = self::where('staff_code', 'like', "$yearPrefix%")
            ->max('staff_code');

        $nextNumber = $lastStaffCode ? (int)substr($lastStaffCode, 4) + 1 : 1;

        return sprintf("%s%04d", $yearPrefix, $nextNumber); // Ví dụ: 00250001
    }
}
