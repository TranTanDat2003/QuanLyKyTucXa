<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Feedback;
use App\Models\Room;
use App\Models\Student;
use App\Models\Utility;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Tổng số phòng
        $totalRooms = Room::count();

        // Tổng số sinh viên
        $totalStudents = Student::count();

        // Hợp đồng chờ duyệt
        $pendingContracts = Contract::where('status', 'Chờ duyệt')->count();

        // Phản hồi chưa xử lý
        $pendingFeedbacks = Feedback::where('status', 'pending')->count();

        // Trạng thái phòng
        $roomStatus = Room::select('status')
            ->groupBy('status')
            ->pluck('status')
            ->mapWithKeys(function ($status) {
                return [$status => Room::where('status', $status)->count()];
            })
            ->toArray();

        // Tiện ích tiêu thụ
        $totalElectricityUsage = Utility::sum('electricity_usage');
        $totalWaterUsage = Utility::sum('water_usage');

        // Hợp đồng gần đây
        $recentContracts = Contract::with(['student', 'room'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Phản hồi gần đây
        $recentFeedbacks = Feedback::with(['student', 'room'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRooms',
            'totalStudents',
            'pendingContracts',
            'pendingFeedbacks',
            'roomStatus',
            'totalElectricityUsage',
            'totalWaterUsage',
            'recentContracts',
            'recentFeedbacks'
        ));
    }
}
