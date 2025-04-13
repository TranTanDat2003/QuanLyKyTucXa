<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomTypeRequest;
use App\Models\RoomType;
use App\Models\Semester;
use App\Traits\CommonFunctions;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    use CommonFunctions;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roomTypes = RoomType::all();
            return response()->json([
                'success' => true,
                'roomTypes' => $roomTypes
            ], 200);
        }

        return view('admin.room_types.index');
    }

    public function store(RoomTypeRequest $request)
    {
        try {
            $validated = $request->validated(); // Lấy dữ liệu đã xác thực

            $name_image = $this->processFileName($validated['room_type_name']);

            $image = $request->file('room_type_img');
            if (!$image) {
                throw new \Exception('File ảnh không tồn tại.');
            }

            $generatedImageName = $this->processImageUpload(
                $image,
                'images/room_types',
                $name_image
            );

            RoomType::create([
                'room_type_name' => $validated['room_type_name'],
                'capacity' => $validated['capacity'],
                'has_air_conditioner' => $validated['has_air_conditioner'] ?? 0,
                'allow_cooking' => $validated['allow_cooking'] ?? 0,
                'room_type_price' => $validated['room_type_price'],
                'room_type_img_path' => $generatedImageName,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thêm loại phòng thành công'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Thêm loại phòng thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showForStudent($roomTypeId)
    {
        try {
            $roomType = RoomType::with(['rooms' => function ($query) {
                $query->where('status', 'Đang sử dụng');
            }])->findOrFail($roomTypeId);

            $semester = Semester::getCurrentSemester() ?? Semester::getNextSemester();
            $semesterId = $semester ? $semester->semester_id : null;

            $totalCapacity = $roomType->rooms->sum('room_type.capacity');
            $occupiedSlots = $roomType->rooms->sum(function ($room) use ($semesterId) {
                return $room->contracts()->where('semester_id', $semesterId)
                    ->where('status', 'Đang ở')
                    ->count();
            });
            $availableSlots = max(0, $totalCapacity - $occupiedSlots);

            $rooms = $roomType->rooms->map(function ($room) {
                return [
                    'room_code' => $room->room_code,
                    'available_slots' => $room->available_slots,
                    'status' => $room->status,
                    'gender' => $room->gender,
                ];
            });

            return response()->json([
                'success' => true,
                'roomTypes' => [
                    'room_type_id' => $roomType->room_type_id,
                    'room_type_name' => $roomType->room_type_name,
                    'capacity' => $roomType->capacity,
                    'room_type_price' => $roomType->room_type_price,
                    'has_air_conditioner' => $roomType->has_air_conditioner,
                    'allow_cooking' => $roomType->allow_cooking,
                    'room_type_img_path' => $roomType->room_type_img_path,
                    'available_slots' => $availableSlots, // Thêm available_slots
                ],
                'rooms' => $rooms
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy dữ liệu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($roomTypeId)
    {
        try {
            $roomType = RoomType::with('rooms')->findOrFail($roomTypeId);

            $rooms = $roomType->getRooms()->get()->map(function ($room) {
                return [
                    'room_code' => $room->room_code,
                    'available_slots' => $room->available_slots,
                    'status' => $room->status,
                    'gender' => $room->gender,
                ];
            });

            return response()->json([
                'success' => true,
                'roomTypes' => [
                    'room_type_id' => $roomType->room_type_id,
                    'room_type_name' => $roomType->room_type_name,
                    'capacity' => $roomType->capacity,
                    'room_type_price' => $roomType->room_type_price,
                    'has_air_conditioner' => $roomType->has_air_conditioner,
                    'allow_cooking' => $roomType->allow_cooking,
                    'room_type_img_path' => $roomType->room_type_img_path,
                ],
                'rooms' => $rooms // Danh sách rooms
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy dữ liệu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(RoomTypeRequest $request, $roomTypeId)
    {
        try {
            $roomType = RoomType::findOrFail($roomTypeId);

            $validated = $request->validated(); // Lấy dữ liệu đã xác thực

            if ($request->hasFile('room_type_img')) {
                $name_image = $this->processFileName($validated['room_type_name']);

                $image = $request->file('room_type_img');

                $generatedImageName = $this->processImageUpload(
                    $image,
                    'images/room_types',
                    $name_image,
                    $roomType->room_type_img_path
                );
            } else {
                $generatedImageName = $roomType->room_type_img_path;
            }

            $roomType->update([
                'room_type_name' => $validated['room_type_name'],
                'capacity' => $validated['capacity'],
                'has_air_conditioner' => $validated['has_air_conditioner'] ?? 0,
                'allow_cooking' => $validated['allow_cooking'] ?? 0,
                'room_type_price' => $validated['room_type_price'],
                'room_type_img_path' => $generatedImageName,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật loại phòng thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật loại phòng' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($roomTypeId)
    {
        try {
            $roomType = RoomType::findOrFail($roomTypeId);

            // Xoá file ảnh trong thư mục
            if (file_exists(public_path('images/room_types/' . $roomType->room_type_img_path))) {
                unlink(public_path('images/room_types/' . $roomType->room_type_img_path));
            }

            $roomType->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa loại phòng thành công!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xóa loại phòng thất bại: ' . $e->getMessage()
            ], 500);
        }
    }
}
