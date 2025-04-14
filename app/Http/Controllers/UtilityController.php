<?php

namespace App\Http\Controllers;

use App\Http\Requests\UtilityRequest;
use App\Models\Room;
use App\Models\Semester;
use App\Models\Utility;
use App\Models\UtilityRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UtilityController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $rooms = Room::getRoomsWithRelations();
                $currentRate = UtilityRate::getRateActive();

                return response()->json([
                    'success' => true,
                    'rooms' => $rooms,
                    'currentRate' => $currentRate ? [
                        'rate_id' => $currentRate->rate_id,
                        'electricity_rate' => $currentRate->electricity_rate,
                        'water_rate' => $currentRate->water_rate
                    ] : null
                ], 200);
            }

            return view('admin.utilities.index');
        } catch (\Exception $e) {
            Log::error('Lỗi trong UtilityController::index: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(UtilityRequest $request)
    {
        try {
            $validated = $request->validated();
            $room = Room::findOrFail($validated['room_id']);
            $rate = UtilityRate::findOrFail($validated['rate_id']);
            $currentUser = Auth::user();

            $currentSemester = Semester::getCurrentSemester();

            if (!$currentSemester) {
                return response()->json([
                    'success' => false,
                    'message' => 'Thời gian hiện tại không trong học kỳ nào'
                ], 404);
            }

            if (!$currentUser || !$currentUser->staff) {
                return response()->json([
                    'success' => false,
                    'message' => 'Người dùng không hợp lệ'
                ], 403);
            }

            Utility::create([
                'month' => $validated['month'],
                'electricity_reading' => $validated['electricity_reading'],
                'water_reading' => $validated['water_reading'],
                'room_id' => $validated['room_id'],
                'rate_id' => $validated['rate_id'],
                'created_by' => $currentUser->staff->staff_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thêm tiện ích thành công'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Lỗi trong UtilityController::store: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Thêm tiện ích thất bại: ' . $e->getMessage()
            ], $e instanceof ValidationException ? 422 : 500);
        }
    }

    public function show($roomId)
    {
        try {
            $room = Room::findOrFail($roomId);
            $utilities = Utility::getByRoomId($roomId);

            return response()->json([
                'success' => true,
                'room_code' => $room->room_code,
                'utilities' => $utilities->map(function ($utility) {
                    return [
                        'utility_id' => $utility->utility_id,
                        'month' => $utility->month->format('m/Y'),
                        'electricity_reading' => $utility->electricity_reading,
                        'water_reading' => $utility->water_reading,
                        'electricity_usage' => $utility->electricity_usage,
                        'water_usage' => $utility->water_usage,
                        'utility_cost' => $utility->utility_cost,
                        'created_by' => $utility->createdBy ? $utility->createdBy->full_name : 'N/A',
                        'updated_by' => $utility->updatedBy ? $utility->updatedBy->full_name : 'N/A',
                        'rate_id' => $utility->rate_id
                    ];
                })
            ], 200);
        } catch (\Exception $e) {
            Log::error('Lỗi trong UtilityController::show: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy dữ liệu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(UtilityRequest $request, $utilityId)
    {
        try {
            $utility = Utility::findOrFail($utilityId);
            $validated = $request->validated();
            $rate = UtilityRate::findOrFail($validated['rate_id']);
            $currentUser = Auth::user();

            if (!$currentUser || !$currentUser->staff) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chỉ nhân viên mới có quyền cập nhật tiện ích'
                ], 403);
            }

            $utility->update([
                'month' => $validated['month'],
                'electricity_reading' => $validated['electricity_reading'],
                'water_reading' => $validated['water_reading'],
                'room_id' => $validated['room_id'],
                'rate_id' => $validated['rate_id'],
                'updated_by' => $currentUser->staff->staff_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật tiện ích thành công'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Lỗi trong UtilityController::update: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Cập nhật tiện ích thất bại: ' . $e->getMessage()
            ], $e instanceof ValidationException ? 422 : 500);
        }
    }
}
