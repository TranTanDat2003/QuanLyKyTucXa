<?php

namespace App\Observers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use App\Mail\StudentAccountCreated;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class StudentObserver
{
    public function creating(Student $student)
    {
        DB::transaction(function () use ($student) {
            if (!$student->student_code) {
                $student->student_code = Student::generateStudentCode($student->enrollment_year);
            }

            $temporaryPassword = Str::password(8);

            $user = User::create([
                'username' => $student->student_code,
                'role' => 'student',
                'status' => 1,
                'password' => Hash::make($temporaryPassword),
            ]);

            $student->user_id = $user->id;

            // Lưu temporaryPassword vào Cache với key là student_code
            Cache::put('temporary_password_' . $student->student_code, $temporaryPassword, now()->addMinutes(10));
        });
    }

    public function created(Student $student)
    {
        try {
            // Lấy temporaryPassword từ Cache
            $temporaryPassword = Cache::get('temporary_password_' . $student->student_code);
            if (!$temporaryPassword) {
                throw new \Exception('Mật khẩu tạm thời chưa được lưu hoặc đã hết hạn.');
            }

            Mail::to($student->email)->queue(new StudentAccountCreated($student, $temporaryPassword));

            // Xóa temporaryPassword khỏi Cache sau khi dùng
            Cache::forget('temporary_password_' . $student->student_code);
        } catch (\Exception $e) {
            Log::error('Lỗi khi gửi mail thông tin tài khoản sinh viên:', [
                'student_id' => $student->student_id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
