<?php

namespace App\Observers;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StaffObserver
{
    public function creating(Staff $staff)
    {
        DB::transaction(function () use ($staff) {
            if (!$staff->staff_code) {
                $staff->staff_code = Staff::generateStaffCode();
            }

            $user = User::create([
                'username' => $staff->staff_code,
                'role' => 'staff',
                'status' => 1,
                'password' => Hash::make(Str::password(8)),
            ]);

            $staff->user_id = $user->id;
        });
    }
}
