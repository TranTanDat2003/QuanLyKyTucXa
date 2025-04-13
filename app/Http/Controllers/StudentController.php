<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\ServiceBill;
use App\Models\Student;
use App\Traits\CommonFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    use CommonFunctions;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $students = Student::with('user')->get();
                return response()->json([
                    'success' => true,
                    'students' => $students->map(function ($student) {
                        return [
                            'student_id' => $student->student_id,
                            'student_code' => $student->student_code,
                            'full_name' => $student->full_name,
                            'date_of_birth' => $student->date_of_birth,
                            'gender' => $student->gender,
                            'email' => $student->email,
                            'enrollment_year' => $student->enrollment_year,
                            'status' => $student->user ? $student->user->status : 0,
                        ];
                    })
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi khi tải dữ liệu: ' . $e->getMessage()
                ], 500);
            }
        }
        return view('admin.students.index');
    }

    public function store(StudentRequest $request)
    {
        try {
            $validated = $request->validated();

            $generatedImageName = $request->file('image')
                ? $this->processImageUpload(
                    $request->file('image'),
                    'images/profiles/students',
                    $this->processFileName($validated['full_name'])
                )
                : 'default_profile.jpg';

            $student = Student::create([
                'full_name' => $validated['full_name'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'email' => $validated['email'],
                'major' => $validated['major'],
                'class' => $validated['class'],
                'enrollment_year' => $validated['enrollment_year'],
                'image' => $generatedImageName,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Thêm sinh viên thành công, Mã sinh viên: {$student->student_code}"
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Thêm sinh viên thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($studentId)
    {
        try {
            $student = Student::with(['user', 'serviceBills.items'])->findOrFail($studentId);

            $serviceBills = $student->serviceBills->map(function ($bill) {
                return [
                    'service_bill_id' => $bill->service_bill_id,
                    'total_amount' => $bill->total_amount,
                    'amount_paid' => $bill->amount_paid,
                    'issued_date' => $bill->issued_date,
                    'due_date' => $bill->due_date,
                    'status' => $bill->status,
                ];
            });

            return response()->json([
                'success' => true,
                'students' => [
                    'student_id' => $student->student_id,
                    'student_code' => $student->student_code,
                    'full_name' => $student->full_name,
                    'date_of_birth' => $student->date_of_birth,
                    'gender' => $student->gender,
                    'phone' => $student->phone,
                    'address' => $student->address,
                    'email' => $student->email,
                    'major' => $student->major,
                    'class' => $student->class,
                    'image' => $student->image,
                    'status' => $student->user->status,
                ],
                'service_bills' => $serviceBills
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy dữ liệu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(StudentRequest $request, $studentId)
    {
        try {
            $student = Student::findOrFail($studentId);
            $validated = $request->validated();

            // Kiểm tra ảnh có thay đổi không
            if ($request->hasFile('image')) {
                $name_image = $this->processFileName($validated['full_name']);
                $image = $request->file('image');
                $generatedImageName = $this->processImageUpload($image, 'images/profiles/students', $name_image, $student->image);
            } else {
                $generatedImageName = $student->image;
            }

            // Chỉ admin hoặc staff mới được dùng hàm này (đã kiểm tra qua middleware)
            DB::transaction(function () use ($student, $validated, $generatedImageName) {
                // Kiểm tra nếu enrollment_year thay đổi thì tạo mã sinh viên mới
                if ($validated['enrollment_year'] !== $student->enrollment_year) {
                    $student->student_code = Student::generateStudentCode($validated['enrollment_year']);
                    if ($student->user) {
                        $student->user->update(['username' => $student->student_code]);
                    }
                }

                $student->update([
                    'full_name' => $validated['full_name'],
                    'date_of_birth' => $validated['date_of_birth'],
                    'gender' => $validated['gender'],
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                    'email' => $validated['email'],
                    'major' => $validated['major'],
                    'class' => $validated['class'],
                    'enrollment_year' => $validated['enrollment_year'],
                    'image' => $generatedImageName,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật sinh viên thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cập nhật sinh viên thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($studentId)
    {
        try {
            DB::transaction(function () use ($studentId) {
                $student = Student::findOrFail($studentId);
                if ($student->image && file_exists(public_path('images/profiles/students/' . $student->image))) {
                    unlink(public_path('images/profiles/students/' . $student->image));
                }
                $student->user()->delete();
                $student->delete();
            });
            return response()->json(['success' => true, 'message' => 'Xóa sinh viên thành công'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Xóa thất bại: ' . $e->getMessage()], 500);
        }
    }

    public function getServiceBillItems($studentId, $serviceBillId)
    {
        try {
            // Kiểm tra hóa đơn thuộc về sinh viên
            $bill = ServiceBill::where('service_bill_id', $serviceBillId)
                ->where('student_id', $studentId)
                ->with('items.service')
                ->firstOrFail();

            $items = $bill->items->map(function ($item) {
                return [
                    'service_name' => $item->service->service_name,
                    'service_price' => $item->service_price,
                    'total_amount' => $item->total_amount,
                    'start_date' => $item->start_date,
                    'end_date' => $item->end_date,
                ];
            });

            return response()->json([
                'success' => true,
                'items' => $items
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy chi tiết hóa đơn: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        try {
            $student = Auth::user()->student;
            $validated = $request->validated();

            // Kiểm tra ảnh có thay đổi không
            if ($request->hasFile('image')) {
                $name_image = $this->processFileName($validated['full_name']);
                $image = $request->file('image');
                $generatedImageName = $this->processImageUpload($image, 'images/profiles/students', $name_image, $student->image);
            } else {
                $generatedImageName = $student->image;
            }

            // Chỉ cập nhật các trường cho phép
            $student->update([
                'full_name' => $validated['full_name'],
                'date_of_birth' => $validated['date_of_birth'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'email' => $validated['email'],
                'major' => $validated['major'],
                'class' => $validated['class'],
                'image' => $generatedImageName,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thông tin thành công',
                'student' => $student
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cập nhật thông tin thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $validated = $request->validated();

            if (!Hash::check($validated['old_password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mật khẩu cũ không đúng'
                ], 422);
            }

            $user->password = Hash::make($validated['new_password']);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Đổi mật khẩu thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đổi mật khẩu thất bại: ' . $e->getMessage()
            ], 500);
        }
    }
}
