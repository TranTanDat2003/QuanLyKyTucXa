<?php

namespace App\Http\Controllers;

use App\Http\Requests\UtilityRateRequest;
use App\Models\UtilityRate;
use Illuminate\Http\Request;

class UtilityRateController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $utilityRates = UtilityRate::all();
            return response()->json([
                'success' => true,
                'utilityRates' => $utilityRates
            ], 200);
        }

        return view('admin.utility_rates.index');
    }

    public function store(UtilityRateRequest $request)
    {
        try {
            $validated = $request->validated();

            UtilityRate::create([
                'electricity_rate' => $validated['electricity_rate'],
                'water_rate' => $validated['water_rate'],
                'effective_date' => $validated['effective_date'],
            ]);

            // Cập nhật status tự động sau khi tạo
            UtilityRate::updateStatus();

            return response()->json([
                'success' => true,
                'message' => 'Thêm giá tiện ích thành công'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Thêm giá tiện ích thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(UtilityRateRequest $request, $rateId)
    {
        try {
            $utilityRate = UtilityRate::findOrFail($rateId);
            $validated = $request->validated();

            $utilityRate->update([
                'electricity_rate' => $validated['electricity_rate'],
                'water_rate' => $validated['water_rate'],
                'effective_date' => $validated['effective_date'],
            ]);

            // Cập nhật status tự động sau khi cập nhật
            UtilityRate::updateStatus();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật giá tiện ích thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật giá tiện ích: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($rateId)
    {
        try {
            $utilityRate = UtilityRate::findOrFail($rateId);
            $utilityRate->delete();

            // Cập nhật status tự động sau khi xóa
            UtilityRate::updateStatus();

            return response()->json([
                'success' => true,
                'message' => 'Xóa giá tiện ích thành công!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xóa giá tiện ích thất bại: ' . $e->getMessage()
            ], 500);
        }
    }
}
