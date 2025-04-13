<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Contract;
use App\Models\Feedback;
use App\Models\Room;
use App\Models\Semester;
use App\Models\ServiceBill;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Utility;
use App\Models\UtilityBill;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $data = Cache::remember('statistics_data', 60 * 60, function () {
            // Lấy danh sách học kỳ
            $semesters = Semester::orderBy('start_date', 'asc')->get()->keyBy('semester_id');

            // Tính doanh thu hợp đồng theo học kỳ
            $contractRevenueData = Contract::select(
                'semester_id',
                DB::raw('SUM(contract_cost) as contract_revenue')
            )
                ->whereNotNull('semester_id')
                ->groupBy('semester_id')
                ->get()
                ->keyBy('semester_id')
                ->toArray();

            // Tính doanh thu dịch vụ theo học kỳ
            $serviceRevenueData = ServiceBill::select(
                'semester_id',
                DB::raw('SUM(total_amount) as service_revenue')
            )
                ->whereNotNull('semester_id')
                ->where('status', 'paid')
                ->groupBy('semester_id')
                ->get()
                ->keyBy('semester_id')
                ->toArray();

            // Kết hợp dữ liệu doanh thu theo học kỳ
            $semesterIds = array_unique(
                array_merge(
                    array_keys($contractRevenueData),
                    array_keys($serviceRevenueData)
                )
            );

            $revenueStats = [
                'labels' => [],
                'contract_revenue' => [],
                'service_revenue' => [],
            ];

            foreach ($semesterIds as $semesterId) {
                if (isset($semesters[$semesterId])) {
                    // Ghép semester_name và academic_year
                    $revenueStats['labels'][] = $semesters[$semesterId]->semester_name . ', ' . $semesters[$semesterId]->academic_year;
                    $revenueStats['contract_revenue'][] = isset($contractRevenueData[$semesterId])
                        ? $contractRevenueData[$semesterId]['contract_revenue']
                        : 0;
                    $revenueStats['service_revenue'][] = isset($serviceRevenueData[$semesterId])
                        ? $serviceRevenueData[$semesterId]['service_revenue']
                        : 0;
                }
            }

            // Tính tỷ lệ phòng
            $roomStats = [
                'available' => Room::where('status', 'Đang sử dụng')
                    ->where('available_slots', '>', 0)
                    ->count(),
                'occupied' => Room::where('status', 'Đang sử dụng')
                    ->where('available_slots', 0)
                    ->count(),
            ];

            // Tính số lượng phản hồi theo trạng thái
            $feedbackStats = [
                'pending' => Feedback::where('status', 'pending')->count(),
                'approved' => Feedback::where('status', 'approved')->count(),
                'rejected' => Feedback::where('status', 'rejected')->count(),
            ];

            // Tính mức sử dụng điện/nước trung bình theo tháng
            $utilityData = Utility::select(
                DB::raw("DATE_FORMAT(month, '%Y-%m') as month"),
                DB::raw('AVG(electricity_usage) as avg_electricity'),
                DB::raw('AVG(water_usage) as avg_water')
            )
                ->groupBy(DB::raw("DATE_FORMAT(month, '%Y-%m')"))
                ->orderBy('month')
                ->get()
                ->toArray();

            $utilityStats = [
                'labels' => [],
                'avg_electricity' => [],
                'avg_water' => [],
            ];

            foreach ($utilityData as $data) {
                $month = Carbon::parse($data['month'])->format('Y-m');
                $utilityStats['labels'][] = $month;
                $utilityStats['avg_electricity'][] = round($data['avg_electricity'], 2);
                $utilityStats['avg_water'][] = round($data['avg_water'], 2);
            }

            return [
                'total_students' => Student::count(),
                'total_staff' => Staff::count(),
                'total_buildings' => Building::count(),
                'total_rooms' => Room::count(),
                'available_rooms' => Room::where('status', 'Đang sử dụng')->where('available_slots', '>', 0)->count(),
                'total_contracts' => Contract::count(),
                'active_contracts' => Contract::where('status', 'Đang ở')->count(),
                'pending_contracts' => Contract::where('status', 'Chờ duyệt')->count(),
                'total_service_bills' => ServiceBill::count(),
                'pending_service_bills' => ServiceBill::where('status', 'pending')->count(),
                'total_utility_bills' => UtilityBill::count(),
                'unpaid_utility_bills' => UtilityBill::where('is_paid', false)->count(),
                'total_feedback' => Feedback::count(),
                'pending_feedback' => Feedback::where('status', 'pending')->count(),
                'current_semester' => Semester::getCurrentSemester(),
                'contract_stats' => [
                    'pending' => Contract::where('status', 'Chờ duyệt')->count(),
                    'approved' => Contract::where('status', 'Đã duyệt')->count(),
                    'active' => Contract::where('status', 'Đang ở')->count(),
                    'expired' => Contract::where('status', 'Hết hạn')->count(),
                    'canceled' => Contract::where('status', 'Hủy')->count(),
                ],
                'revenue_stats' => $revenueStats,
                'room_stats' => $roomStats,
                'feedback_stats' => $feedbackStats,
                'utility_stats' => $utilityStats,
            ];
        });

        return view('admin.reports.index', $data);
    }

    public function utilities(Request $request)
    {
        // Lấy tháng được chọn từ request, nếu không có thì lấy tháng hiện tại
        $selectedMonth = $request->input('month', now()->format('Y-m'));

        // Lấy danh sách các tháng duy nhất từ bảng utilities
        $uniqueMonths = Utility::select('month')
            ->distinct()
            ->orderBy('month', 'desc')
            ->pluck('month')
            ->map(function ($month) {
                return Carbon::parse($month)->format('Y-m');
            })->toArray();

        // Lấy dữ liệu tiện ích theo tháng, nhóm theo tòa nhà
        $utilities = Utility::whereRaw("DATE_FORMAT(month, '%Y-%m') = ?", [$selectedMonth])
            ->join('rooms', 'utilities.room_id', '=', 'rooms.room_id')
            ->join('buildings', 'rooms.building_id', '=', 'buildings.building_id')
            ->leftJoin('utility_bills', 'utilities.utility_id', '=', 'utility_bills.utility_id') // Join with utility_bills
            ->with(['room', 'utilityBills.contract.student'])
            ->select(
                'buildings.building_id',
                'buildings.building_name',
                DB::raw('AVG(utilities.electricity_usage) as avg_electricity_usage'),
                DB::raw('AVG(utilities.water_usage) as avg_water_usage'),
                DB::raw('SUM(utilities.utility_cost) as total_utility_cost'),
                DB::raw('COALESCE(SUM(utility_bills.amount_paid), 0) as total_amount_paid') // Use COALESCE to handle NULL
            )
            ->groupBy('buildings.building_id', 'buildings.building_name')
            ->get()
            ->map(function ($building) use ($selectedMonth) {
                $utilities = Utility::whereRaw("DATE_FORMAT(month, '%Y-%m') = ?", [$selectedMonth])
                    ->join('rooms', 'utilities.room_id', '=', 'rooms.room_id')
                    ->where('rooms.building_id', $building->building_id)
                    ->with(['room', 'utilityBills.contract.student'])
                    ->get()
                    ->map(function ($utility) {
                        $room = $utility->room;
                        $utilityBills = $utility->utilityBills;

                        // Kiểm tra trạng thái thanh toán của phòng
                        $totalShareAmount = $utilityBills->sum('share_amount');
                        $totalPaid = $utilityBills->sum('amount_paid');
                        $isFullyPaid = $totalShareAmount > 0 && $totalPaid >= $totalShareAmount;

                        // Lấy danh sách sinh viên và trạng thái thanh toán
                        $students = $utilityBills->map(function ($bill) {
                            return [
                                'student_id' => $bill->contract->student->student_id,
                                'student_code' => $bill->contract->student->student_code,
                                'full_name' => $bill->contract->student->full_name,
                                'share_amount' => $bill->share_amount,
                                'amount_paid' => $bill->amount_paid,
                                'is_paid' => $bill->is_paid,
                            ];
                        });

                        return [
                            'room_id' => $room->room_id,
                            'room_code' => $room->room_code,
                            'utility_id' => $utility->utility_id,
                            'electricity_usage' => $utility->electricity_usage,
                            'water_usage' => $utility->water_usage,
                            'utility_cost' => $utility->utility_cost,
                            'is_fully_paid' => $isFullyPaid,
                            'students' => $students,
                        ];
                    });

                return [
                    'building_id' => $building->building_id,
                    'building_name' => $building->building_name,
                    'avg_electricity_usage' => $building->avg_electricity_usage,
                    'avg_water_usage' => $building->avg_water_usage,
                    'total_utility_cost' => $building->total_utility_cost,
                    'total_amount_paid' => $building->total_amount_paid,
                    'rooms' => $utilities,
                ];
            });

        // Chuẩn bị dữ liệu cho biểu đồ
        $chartData = [
            'labels' => $utilities->pluck('building_name')->toArray(),
            'electricity_usage' => $utilities->pluck('avg_electricity_usage')->toArray(),
            'water_usage' => $utilities->pluck('avg_water_usage')->toArray(),
            'utility_cost' => $utilities->pluck('total_utility_cost')->toArray(),
            'amount_paid' => $utilities->pluck('total_amount_paid')->toArray(),
        ];

        return view('admin.reports.utilities', compact('utilities', 'uniqueMonths', 'selectedMonth', 'chartData'));
    }
}
