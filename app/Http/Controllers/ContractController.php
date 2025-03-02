<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContractRequest;
use App\Models\Contract;
use App\Models\RoomBill;
use App\Models\Room;
use App\Models\Semester;
use App\Models\Student;
use App\Mail\ContractApproved;
use Illuminate\Support\Facades\Mail;

class ContractController extends Controller
{
    public function index()
    {
        $rooms = Room::getAvailableRooms();
        $semesters = Semester::all();
        return view('contracts.index', compact('rooms', 'semesters'));
    }

    public function store(StoreContractRequest $request)
    {
        $currentSemester = Semester::getCurrentSemester();
        if (Contract::hasExistingContract($request->student_id, $currentSemester->semester_id)) {
            return back()->withErrors(['student_id' => 'Bạn đã đăng ký phòng trong học kỳ này!']);
        }

        $room = Room::findOrFail($request->room_id);
        $student = Student::findOrFail($request->student_id);
        if ($room->gender !== 'Cả hai' && $room->gender !== $student->gender) {
            return back()->withErrors(['room_id' => 'Phòng chỉ dành cho ' . $room->gender]);
        }

        $contract = Contract::create($request->all() + ['status' => 'Chờ duyệt', 'is_paid' => false]);
        RoomBill::create([
            'contract_id' => $contract->contract_id,
            'student_id' => $contract->student_id,
            'semester_id' => $contract->semester_id,
            'room_cost' => $room->price,
            'issue_date' => $contract->start_date,
            'due_date' => $contract->start_date->addDays(7),
        ]);

        return redirect('/')->with('message', 'Đăng ký đã được gửi, vui lòng đợi duyệt!');
    }
}
