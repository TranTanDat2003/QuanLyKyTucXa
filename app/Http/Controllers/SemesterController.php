<?php

namespace App\Http\Controllers;

use App\Http\Requests\SemesterRequest;
use App\Models\Semester;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SemesterController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $semesters = Semester::all();
            return response()->json([
                'success' => true,
                'semesters' => $semesters
            ], 200);
        }

        return view('admin.semesters.index');
    }

    public function store(SemesterRequest $request)
    {
        try {
            $validated = $request->validated();
            $now = Carbon::now();

            // Tính status dựa trên ngày hiện tại
            $status = ($now->gte($validated['start_date']) && $now->lte($validated['end_date'])) ? 1 : 0;

            Semester::create([
                'semester_name' => $validated['semester_name'],
                'academic_year' => $validated['academic_year'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'status' => $status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thêm học kỳ thành công'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Thêm học kỳ thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($semesterId)
    {
        try {
            $semester = Semester::with('contracts')->findOrFail($semesterId);

            $contracts = $semester->contracts()->get()->map(function ($contract) {
                return [
                    'contract_id' => $contract->contract_id,
                    'student_id' => $contract->student_id,
                    'room_id' => $contract->room_id,
                    'status' => $contract->status,
                ];
            });

            return response()->json([
                'success' => true,
                'semester' => [
                    'semester_id' => $semester->semester_id,
                    'semester_name' => $semester->semester_name,
                    'academic_year' => $semester->academic_year,
                    'start_date' => $semester->start_date->toDateString(),
                    'end_date' => $semester->end_date->toDateString(),
                    'status' => $semester->status,
                ],
                'contracts' => $contracts
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy dữ liệu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(SemesterRequest $request, $semesterId)
    {
        try {
            $semester = Semester::findOrFail($semesterId);
            $validated = $request->validated();
            $now = Carbon::now();

            // Tính status dựa trên ngày hiện tại
            $status = ($now->gte($validated['start_date']) && $now->lte($validated['end_date'])) ? 1 : 0;

            $semester->update([
                'semester_name' => $validated['semester_name'],
                'academic_year' => $validated['academic_year'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'status' => $status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật học kỳ thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật học kỳ: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($semesterId)
    {
        try {
            $semester = Semester::findOrFail($semesterId);
            $semester->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa học kỳ thành công!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xóa học kỳ thất bại: ' . $e->getMessage()
            ], 500);
        }
    }
}
