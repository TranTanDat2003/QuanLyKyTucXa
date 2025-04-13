<?php

namespace App\Observers;

use App\Mail\ContractApproved;
use App\Models\Contract;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContractObserver
{
    /**
     * Handle the Contract "updated" event.
     */
    public function updated(Contract $contract): void
    {
        // Kiểm tra nếu status thay đổi thành 'Đã duyệt'
        if ($contract->isDirty('status') && $contract->status === 'Đã duyệt') {
            try {
                Mail::to($contract->student->email)->queue(new ContractApproved($contract));
            } catch (\Exception $e) {
                Log::error('Failed to send contract approval email:', [
                    'contract_id' => $contract->contract_id,
                    'student_email' => $contract->student->email,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
