<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUtilityRequest;
use App\Models\Contract;
use App\Models\Room;
use App\Models\Utility;
use App\Models\UtilityBill;
use App\Models\UtilityRate;

class UtilityController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        return view('utilities.index', compact('rooms'));
    }

    public function store(StoreUtilityRequest $request)
    {
        $rate = UtilityRate::where('effective_date', '<=', $request->month)->orderBy('effective_date', 'desc')->first();
        $electricityCost = $request->electricity_usage * $rate->electricity_rate;
        $waterCost = $request->water_usage * $rate->water_rate;

        $utility = Utility::updateOrCreate(
            ['room_id' => $request->room_id, 'month' => $request->month],
            ['electricity_usage' => $request->electricity_usage, 'water_usage' => $request->water_usage, 'electricity_cost' => $electricityCost, 'water_cost' => $waterCost]
        );

        $contracts = Contract::where('room_id', $utility->room_id)->where('status', 'Đang thuê')->get();
        $totalStudents = $contracts->count();
        foreach ($contracts as $contract) {
            $serviceCost = $contract->student->services()->sum('services.price');
            UtilityBill::updateOrCreate(
                ['utility_id' => $utility->utility_id, 'student_id' => $contract->student_id],
                ['electricity_cost' => $utility->electricity_cost / $totalStudents, 'water_cost' => $utility->water_cost / $totalStudents, 'service_cost' => $serviceCost, 'issue_date' => $utility->month, 'due_date' => $utility->month->endOfMonth()]
            );
        }

        return redirect('/utilities')->with('message', 'Nhập số điện/nước thành công!');
    }
}
