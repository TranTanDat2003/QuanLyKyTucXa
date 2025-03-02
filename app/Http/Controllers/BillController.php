<?php

namespace App\Http\Controllers;

use App\Models\RoomBill;
use App\Models\UtilityBill;
use Illuminate\Support\Facades\Auth;

class BillController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $roomBills = RoomBill::where('student_id', $user->student_id)->with(['semester', 'contract'])->get();

        $utilityBills = UtilityBill::where('student_id', $user->student_id)
            ->with(['utility.room', 'utility.contracts.semester'])
            ->get();

        $monthlyFees = [];
        $stt = 1;
        foreach ($utilityBills as $bill) {
            $month = date('m', strtotime($bill->issue_date));
            $semester = $bill->utility->contracts->first()->semester;

            if ($bill->electricity_cost > 0) {
                $monthlyFees[] = [
                    'stt' => $stt++,
                    'month' => $month,
                    'type' => 'Đơn giá điện',
                    'usage_cost' => $bill->utility->electricity_cost,
                    'amount' => $bill->electricity_cost,
                    'paid_at' => $bill->paid_at,
                    'semester' => $semester->semester_name,
                ];
            }

            if ($bill->water_cost > 0) {
                $monthlyFees[] = [
                    'stt' => $stt++,
                    'month' => $month,
                    'type' => 'Đơn giá nước',
                    'usage_cost' => $bill->utility->water_cost,
                    'amount' => $bill->water_cost,
                    'paid_at' => $bill->paid_at,
                    'semester' => $semester->semester_name,
                ];
            }
        }

        return view('bills.index', compact('roomBills', 'monthlyFees'));
    }

    public function payRoomBill($roomBillId)
    {
        $roomBill = RoomBill::findOrFail($roomBillId);
        if ($roomBill->status === 'Đã thanh toán') {
            return back()->withErrors(['status' => 'Hóa đơn đã được thanh toán!']);
        }

        $roomBill->update(['status' => 'Đã thanh toán']);
        $contract = $roomBill->contract;
        $allPaid = RoomBill::where('contract_id', $contract->contract_id)->where('status', 'Chưa thanh toán')->doesntExist();
        if ($allPaid) {
            $contract->update(['is_paid' => true]);
        }

        return redirect('/bills')->with('message', 'Thanh toán tiền phòng thành công!');
    }

    public function payUtilityBill($utilityBillId)
    {
        $utilityBill = UtilityBill::findOrFail($utilityBillId);
        if ($utilityBill->status === 'Đã thanh toán') {
            return back()->withErrors(['status' => 'Hóa đơn đã được thanh toán!']);
        }

        $utilityBill->update([
            'status' => 'Đã thanh toán',
            'paid_at' => now(),
        ]);

        return redirect('/bills')->with('message', 'Thanh toán tiền điện/nước thành công!');
    }
}
