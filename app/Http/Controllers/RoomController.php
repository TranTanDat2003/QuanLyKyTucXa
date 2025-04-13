<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomRequest;
use App\Models\Room;
use App\Models\Building;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $rooms = Room::with(['building', 'roomType'])->get();
            return response()->json([
                'success' => true,
                'rooms' => $rooms
            ], 200);
        }
        $buildings = Building::all();
        $roomTypes = RoomType::all();
        return view('admin.rooms.index', compact('buildings', 'roomTypes'));
    }

    public function store(RoomRequest $request)
    {
        try {
            $validated = $request->validated();

            // Lấy building_name từ building_id
            $building = Building::findOrFail($validated['building_id']);
            $fullRoomCode = preg_replace('/\s+/', '', $building->building_name . $validated['room_code']);

            Room::create([
                'room_code' => $fullRoomCode,
                'building_id' => $validated['building_id'],
                'room_type_id' => $validated['room_type_id'],
                'status' => $validated['status'],
                'gender' => $validated['gender'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thêm phòng thành công'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error adding room:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Thêm phòng thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(RoomRequest $request, $roomId)
    {
        try {
            $room = Room::findOrFail($roomId);
            $validated = $request->validated();

            $building = Building::findOrFail($validated['building_id']);
            $fullRoomCode = preg_replace('/\s+/', '', $building->building_name . $validated['room_code']);

            $room->update([
                'room_code' => $fullRoomCode,
                'building_id' => $validated['building_id'],
                'room_type_id' => $validated['room_type_id'],
                'status' => $validated['status'],
                'gender' => $validated['gender'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật phòng thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cập nhật phòng thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($roomId)
    {
        try {
            $room = Room::findOrFail($roomId);
            $room->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa phòng thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xóa phòng thất bại: ' . $e->getMessage()
            ], 500);
        }
    }
}
