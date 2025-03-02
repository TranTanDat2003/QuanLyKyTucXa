<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $primaryKey = 'student_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['student_id', 'full_name', 'date_of_birth', 'gender', 'phone', 'email'];

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'student_id');
    }
    public function utilityBills()
    {
        return $this->hasMany(UtilityBill::class, 'student_id');
    }
}
