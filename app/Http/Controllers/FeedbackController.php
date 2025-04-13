<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedbackRequest;
use App\Models\Contract;
use App\Models\Feedback;
use App\Models\Semester;
use App\Traits\CommonFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    use CommonFunctions;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $feedbacks = Feedback::with(['room', 'student', 'staff'])->get();
            return response()->json([
                'success' => true,
                'feedbacks' => $feedbacks
            ], 200);
        }

        return view('admin.feedbacks.index');
    }

    public function store(FeedbackRequest $request)
    {
        try {
            $validated = $request->validated();
            $currentUser = Auth::user();

            // Kiểm tra student_id từ request có khớp với user đang đăng nhập không
            if ($validated['student_id'] != $currentUser->student->student_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền gửi feedback cho sinh viên khác.'
                ], 403);
            }

            // Lấy học kỳ hiện tại hoặc tiếp theo từ Model
            $semester = Semester::getCurrentSemester() ?? Semester::getNextSemester();

            $contract = Contract::getContractWithStudentAndSemester(
                $validated['student_id'],
                $semester->semester_id
            );

            if (!$contract) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn hiện không ở ký túc xá, không thể gửi yêu cầu sửa chữa.'
                ], 403);
            }

            $image = $request->file('image');
            $generatedImageName = $image ? $this->processImageUpload(
                $image,
                'images/feedbacks',
                $this->processFileName("feedback_{$validated['student_id']}_" . now()->timestamp)
            ) : null;

            Feedback::create([
                'content' => $validated['content'],
                'image' => $generatedImageName,
                'quantity' => $validated['quantity'],
                'status' => 'pending',
                'room_id' => $validated['room_id'],
                'student_id' => $validated['student_id'],
                'staff_id' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Gửi báo cáo lỗi thành công'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gửi báo cáo lỗi thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($feedbackId)
    {
        try {
            $feedback = Feedback::with(['room', 'student', 'staff'])->findOrFail($feedbackId);
            return response()->json([
                'success' => true,
                'feedback' => $feedback
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy dữ liệu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(FeedbackRequest $request, $feedbackId)
    {
        try {
            $feedback = Feedback::findOrFail($feedbackId);
            $validated = $request->validated();
            $currentUser = Auth::user();

            $feedback->update([
                'status' => $validated['status'],
                'scheduled_fix_date' => $validated['scheduled_fix_date'],
                'staff_id' => $currentUser->staff->staff_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật báo cáo lỗi thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cập nhật báo cáo lỗi thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($feedbackId)
    {
        try {
            $feedback = Feedback::findOrFail($feedbackId);

            if ($feedback->image && file_exists(public_path('images/feedbacks/' . $feedback->image))) {
                unlink(public_path('images/feedbacks/' . $feedback->image));
            }

            $feedback->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa báo cáo lỗi thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xóa báo cáo lỗi thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    public function rejectFeedback($feedbackId)
    {
        try {
            $feedback = Feedback::findOrFail($feedbackId);
            $currentUser = Auth::user();

            if (!in_array($currentUser->role, ['admin', 'staff'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền từ chối báo cáo lỗi.'
                ], 403);
            }

            $feedback->update([
                'status' => 'rejected',
                'staff_id' => $currentUser->staff->staff_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đã từ chối báo cáo lỗi'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi từ chối báo cáo lỗi: ' . $e->getMessage()
            ], 500);
        }
    }
}
