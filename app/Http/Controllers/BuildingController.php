<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuildingRequest;
use App\Models\Building;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $buildings = Building::all();
            return response()->json([
                'success' => true,
                'buildings' => $buildings
            ], 200);
        }

        return view('admin.buildings.index');
    }

    public function store(BuildingRequest $request)
    {
        try {
            $validated = $request->validated();

            Building::create([
                'building_name' => $validated['building_name'],
                'description' => $validated['description'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thêm tòa nhà thành công'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Thêm tòa nhà thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(BuildingRequest $request, $buildingId)
    {
        try {
            $building = Building::findOrFail($buildingId);
            $validated = $request->validated();

            $building->update([
                'building_name' => $validated['building_name'],
                'description' => $validated['description'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật tòa nhà thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cập nhật tòa nhà thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($buildingId)
    {
        try {
            $building = Building::findOrFail($buildingId);
            $building->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa tòa nhà thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xóa tòa nhà thất bại: ' . $e->getMessage()
            ], 500);
        }
    }
}
