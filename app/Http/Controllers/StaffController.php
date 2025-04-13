<?php

namespace App\Http\Controllers;

use App\Http\Requests\StaffPasswordRequest;
use App\Http\Requests\StaffProfileRequest;
use App\Http\Requests\StaffRequest;
use App\Models\Staff;
use App\Traits\CommonFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    use CommonFunctions;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $staffs = Staff::with('user')->get();
                return response()->json([
                    'success' => true,
                    'staffs' => $staffs->map(function ($staff) {
                        return [
                            'staff_id' => $staff->staff_id,
                            'staff_code' => $staff->staff_code,
                            'full_name' => $staff->full_name,
                            'date_of_birth' => $staff->date_of_birth,
                            'gender' => $staff->gender,
                            'phone' => $staff->phone,
                            'address' => $staff->address,
                            'email' => $staff->email,
                            'image' => $staff->image,
                            'status' => $staff->user ? $staff->user->status : 0,
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
        return view('admin.staff.index');
    }

    public function store(StaffRequest $request)
    {
        try {
            $validated = $request->validated();

            $generatedImageName = $request->file('image')
                ? $this->processImageUpload(
                    $request->file('image'),
                    'images/profiles/staff',
                    $this->processFileName($validated['full_name'])
                )
                : 'default_profile.jpg';

            $staff = Staff::create([
                'full_name' => $validated['full_name'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'email' => $validated['email'],
                'image' => $generatedImageName,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Thêm nhân viên thành công, Mã nhân viên: {$staff->staff_code}"
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Thêm nhân viên thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($staffId)
    {
        try {
            $staff = Staff::with('user')->findOrFail($staffId);

            return response()->json([
                'success' => true,
                'staffs' => [
                    'staff_id' => $staff->staff_id,
                    'staff_code' => $staff->staff_code,
                    'full_name' => $staff->full_name,
                    'date_of_birth' => $staff->date_of_birth,
                    'gender' => $staff->gender,
                    'phone' => $staff->phone,
                    'address' => $staff->address,
                    'email' => $staff->email,
                    'image' => $staff->image,
                    'status' => $staff->user->status,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy dữ liệu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(StaffRequest $request, $staffId)
    {
        try {
            $staff = Staff::findOrFail($staffId);
            $validated = $request->validated();

            if ($request->hasFile('image')) {
                $name_image = $this->processFileName($validated['full_name']);
                $generatedImageName = $this->processImageUpload($request->file('image'), 'images/profiles/staff', $name_image, $staff->image);
            } else {
                $generatedImageName = $staff->image;
            }

            $staff->update([
                'full_name' => $validated['full_name'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'email' => $validated['email'],
                'image' => $generatedImageName,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật nhân viên thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cập nhật nhân viên thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($staffId)
    {
        try {
            DB::transaction(function () use ($staffId) {
                $staff = Staff::findOrFail($staffId);
                if ($staff->image && file_exists(public_path('images/profiles/staff/' . $staff->image))) {
                    unlink(public_path('images/profiles/staff/' . $staff->image));
                }
                $staff->user()->delete();
                $staff->delete();
            });
            return response()->json([
                'success' => true,
                'message' => 'Xóa nhân viên thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xóa thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    public function profile(Request $request)
    {
        $staff = Auth::user()->staff;
        return view('admin.profiles.index', compact('staff'));
    }

    public function updateProfile(StaffProfileRequest $request)
    {
        try {
            $staff = Auth::user()->staff;
            $validated = $request->validated();

            if ($request->hasFile('image')) {
                $name_image = $this->processFileName($validated['full_name']);
                $generatedImageName = $this->processImageUpload(
                    $request->file('image'),
                    'images/profiles/staff',
                    $name_image,
                    $staff->image
                );
            } else {
                $generatedImageName = $staff->image;
            }

            $staff->update([
                'full_name' => $validated['full_name'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'email' => $validated['email'],
                'image' => $generatedImageName,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thông tin cá nhân thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cập nhật thông tin cá nhân thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showPasswordForm()
    {
        $staff = Auth::user()->staff;
        return view('admin.profiles.change_password', compact('staff'));
    }

    public function updatePassword(StaffPasswordRequest $request)
    {
        try {
            $validated = $request->validated();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Verify current password
            if (!Hash::check($validated['current_password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'current_password' => ['Mật khẩu hiện tại không đúng']
                    ]
                ], 422);
            }

            // Update password
            $user->update([
                'password' => Hash::make($validated['new_password'])
            ]);

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
