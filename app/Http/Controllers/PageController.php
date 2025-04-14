<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Feedback;
use App\Models\RoomType;
use App\Models\Semester;
use App\Models\Service;
use App\Models\ServiceBill;
use App\Models\UtilityBill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function index()
    {
        return view('student.homepage.index');
    }

    public function filter(Request $request)
    {
        // Lấy gender từ sinh viên đang đăng nhập
        $user = Auth::user();
        $gender = $user->student ? $user->student->gender : null;

        // Nếu không có gender, có thể trả về lỗi hoặc dùng giá trị mặc định
        if ($gender === null) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xác định giới tính của sinh viên.'
            ], 400);
        }

        $gender = $gender == 0 ? 'Nam' : 'Nữ';

        $amenities = $request->query('amenities');
        $capacity = $request->query('capacity');

        $roomTypes = RoomType::filterRoomTypesWithAvailableSlots($capacity, $amenities, $gender);
        if ($roomTypes->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy loại phòng nào phù hợp với tiêu chí đã chọn.'
            ], 200);
        }

        return response()->json([
            'success' => true,
            'roomTypes' => $roomTypes
        ], 200);
    }

    public function showRoomBookingPage()
    {
        // Lấy học kỳ hiện tại hoặc tiếp theo từ Model
        $semester = Semester::getCurrentSemester() ?? Semester::getNextSemester();

        // Tính thời gian đăng ký
        $registrationStart = $semester ? $semester->start_date->subWeeks(2) : null;
        $registrationEnd = $semester ? $semester->start_date : null;
        $isRegistrationOpen = $semester && now()->between($registrationStart, $registrationEnd);

        // Truyền dữ liệu sang view
        return view(
            'student.room_booking_page.index',
            compact(
                'semester',
                'registrationStart',
                'registrationEnd',
                'isRegistrationOpen'
            )
        );
    }

    public function showPayPage()
    {
        $studentId = Auth::user()->student->student_id;

        // Lấy hợp đồng của sinh viên
        $contracts = Contract::where('student_id', $studentId)
            ->with(['room', 'roomType', 'semester'])
            ->get();

        // Lấy hóa đơn điện nước
        $utilityBills = UtilityBill::whereHas('contract', function ($query) use ($studentId) {
            $query->where('student_id', $studentId);
        })->with(['utility.room', 'contract.semester'])->get();

        // Lấy hóa đơn dịch vụ
        $serviceBills = ServiceBill::where('student_id', $studentId)
            ->with(['items.service', 'semester'])
            ->get();

        return view('student.pay_page.index', compact('contracts', 'utilityBills', 'serviceBills'));
    }

    public function showServiceBookingPage()
    {
        $semester = Semester::getCurrentSemester() ?? Semester::getNextSemester();
        $registrationStart = $semester ? $semester->start_date->subWeeks(2) : null;
        $registrationEnd = $semester ? $semester->start_date : null;
        $isRegistrationOpen = $semester && now()->between($registrationStart, $registrationEnd);

        return view(
            'student.service_booking_page.index',
            compact('semester', 'registrationStart', 'registrationEnd', 'isRegistrationOpen')
        );
    }

    public function filterServices(Request $request)
    {
        $services = Service::where('is_active', true)->get();

        if ($services->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Không có dịch vụ nào khả dụng.'
            ], 200);
        }

        return response()->json([
            'success' => true,
            'services' => $services
        ], 200);
    }

    public function showFeedbackPage(Request $request)
    {
        // Lấy học kỳ hiện tại hoặc tiếp theo từ Model
        $semester = Semester::getCurrentSemester() ?? Semester::getNextSemester();

        $currentUser = Auth::user();
        $studentId = $currentUser->student ? $currentUser->student->student_id : null;

        if (!$studentId) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin sinh viên.'
            ], 400);
        }

        $currentRoom = Contract::getContractWithStudentAndSemesterAndStatus(
            $studentId,
            $semester->semester_id
        );

        $feedbacks = $currentUser->student->feedbacks;

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'feedbacks' => $feedbacks,
                'currentRoom' => $currentRoom
            ], 200);
        }

        // Truyền dữ liệu vào view
        return view('student.feedback_page.index', compact('currentRoom', 'feedbacks'));
    }

    public function showProfilePage(Request $request)
    {
        $student = Auth::user()->student;
        if (!$student) {
            return redirect()->route('home')->with('error', 'Không tìm thấy thông tin sinh viên.');
        }

        // Lấy hợp đồng hiện tại của sinh viên
        $currentSemester = Semester::getCurrentSemester() ?? Semester::getNextSemester();
        $currentContract = Contract::where('student_id', $student->student_id)
            ->where('semester_id', $currentSemester->semester_id)
            ->whereIn('status', ['Đã duyệt', 'Đang ở'])
            ->with('room')
            ->first();

        // Lấy lịch sử hợp đồng (bookings)
        $bookingHistory = Contract::where('student_id', $student->student_id)
            ->with(['room', 'semester'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Lấy lịch sử thanh toán (payments)
        $paymentHistory = [];
        $contractsPayments = Contract::where('student_id', $student->student_id)
            ->where('is_paid', true)
            ->get()
            ->map(function ($contract) {
                return [
                    'id' => 'R' . $contract->contract_id,
                    'amount' => $contract->contract_cost,
                    'payment_date' => $contract->updated_at->format('d/m/Y'),
                    'type' => 'Tiền phòng',
                ];
            });

        $utilityPayments = UtilityBill::whereHas('contract', function ($query) use ($student) {
            $query->where('student_id', $student->student_id);
        })
            ->where('is_paid', true)
            ->get()
            ->map(function ($bill) {
                return [
                    'id' => 'E' . $bill->utility_bill_id,
                    'amount' => $bill->share_amount,
                    'payment_date' => $bill->paid_at ? $bill->paid_at->format('d/m/Y') : 'Chưa thanh toán',
                    'type' => 'Điện nước',
                ];
            });

        $servicePayments = ServiceBill::where('student_id', $student->student_id)
            ->where('status', 'paid')
            ->get()
            ->map(function ($bill) {
                return [
                    'id' => 'S' . $bill->service_bill_id,
                    'amount' => $bill->total_amount,
                    'payment_date' => $bill->paid_at ? $bill->paid_at->format('d/m/Y') : 'Chưa thanh toán',
                    'type' => 'Dịch vụ',
                ];
            });

        $paymentHistory = $contractsPayments->merge($utilityPayments)->merge($servicePayments)->sortByDesc('payment_date');

        return view('student.profile_page.index', compact('student', 'currentContract', 'bookingHistory', 'paymentHistory'));
    }
}
