<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\UtilityController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\ServiceBillController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UtilityBillController;
use App\Http\Controllers\UtilityRateController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('authentication')->name('logout');

Route::middleware('auth')->group(function () {
    Route::middleware('student')->group(function () {
        Route::get('/', [PageController::class, 'index'])->name('home');

        // Route cho trang đăng ký phòng
        Route::get('/room-booking', [PageController::class, 'showRoomBookingPage'])->name('student.room_booking');
        Route::get('/student/rooms/filter', [PageController::class, 'filter'])->name('student.rooms.filter');
        Route::get('/room-booking/{roomTypeId}', [RoomTypeController::class, 'showForStudent'])->name('room_types.show');
        Route::post('/contracts', [ContractController::class, 'store'])->name('contracts.store');
        Route::post('/contracts/{contractId}/payment', [ContractController::class, 'initiatePayment'])->name('contract.payment');

        // Route cho trang đăng ký dịch vụ
        Route::get('/service-booking', [PageController::class, 'showServiceBookingPage'])->name('student.service_booking');
        Route::get('/student/services/filter', [PageController::class, 'filterServices'])->name('student.services.filter');
        Route::post('/service-bills', [ServiceBillController::class, 'store'])->name('service_bills.store');
        Route::post('/service-bills/{serviceBillId}/payment', [ServiceBillController::class, 'initiatePayment'])->name('service.payment');

        // Route cho trang sửa chữa cơ sở vật chất
        Route::get('/facility-repair', [PageController::class, 'showFeedbackPage'])->name('student.facility_repair');
        Route::post('/feedbacks', [FeedbackController::class, 'store'])->name('feedbacks.store');

        // Route cho trang thanh toán
        Route::get('/payments', [PageController::class, 'showPayPage'])->name('student.pay');
        Route::get('/payment/callback/{method}', [PaymentController::class, 'paymentCallback'])->name('payment.callback');
        Route::post('/payment/return/{method}', [PaymentController::class, 'paymentCallback'])->name('payment.return');

        // Route thanh toán điện nước
        Route::post('/utility-bills/{utilityBillId}/payment', [UtilityBillController::class, 'initiatePayment'])->name('utility.payment');

        // Route cho thông tin sinh viên
        Route::get('/student/profile', [PageController::class, 'showProfilePage'])->name('student.profile');
        Route::put('/student/profile/update', [StudentController::class, 'updateProfile'])->name('student.profile.update');
        Route::put('/student/profile/password', [StudentController::class, 'updatePassword'])->name('student.password.update');

        // Route cho trang chat
        Route::get('/chat', [ChatController::class, 'index'])->name('chat.show');
        Route::post('/chat/message', [ChatController::class, 'sendMessage'])->name('chat.send');
        Route::post('/chat/greet/{receiver}', [ChatController::class, 'greetReceived'])->name('chat.greet');
    });

    Route::middleware('admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin');

        // Routes quản lý hợp đồng
        Route::get('/contracts', [ContractController::class, 'index'])->name('contracts.index');
        Route::post('/contracts/{contractId}/approve', [ContractController::class, 'approve'])->name('contracts.approve');
        Route::post('/contracts/{contractId}/cancel', [ContractController::class, 'cancel'])->name('contracts.cancel');
        Route::post('/contracts/{contractId}/checkout', [ContractController::class, 'checkout'])->name('contracts.checkout');
        Route::get('/contracts/{contractId}', [ContractController::class, 'show'])->name('contracts.show');

        // Routes quản lý tiện ích
        Route::get('/utilities', [UtilityController::class, 'index'])->name('utilities.index');
        Route::post('/utilities', [UtilityController::class, 'store'])->name('utilities.store');
        Route::get('/utilities/{roomId}', [UtilityController::class, 'show'])->name('utilities.show');
        Route::put('/utilities/{utilityId}', [UtilityController::class, 'update'])->name('utilities.update');

        // Routes quản lý phòng
        Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
        Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
        Route::put('/rooms/{roomId}', [RoomController::class, 'update'])->name('rooms.update');
        Route::post('/rooms/{roomId}', [RoomController::class, 'destroy'])->name('rooms.destroy');

        // Routes quản lý loại phòng
        Route::get('/room-types', [RoomTypeController::class, 'index'])->name('room_types.index');
        Route::post('/room-types', [RoomTypeController::class, 'store'])->name('room_types.store');
        Route::put('/room-types/{roomTypeId}', [RoomTypeController::class, 'update'])->name('room_types.update');
        Route::delete('/room-types/{roomTypeId}', [RoomTypeController::class, 'destroy'])->name('room_types.destroy');
        Route::get('/room-types/{roomTypeId}', [RoomTypeController::class, 'show'])->name('room_types.rooms');

        // Routes quản lý toà nhà
        Route::get('/buildings', [BuildingController::class, 'index'])->name('buildings.index');
        Route::post('/buildings', [BuildingController::class, 'store'])->name('buildings.store');
        Route::put('/buildings/{buildingId}', [BuildingController::class, 'update'])->name('buildings.update');
        Route::delete('/buildings/{buildingId}', [BuildingController::class, 'destroy'])->name('buildings.destroy');

        // Routes quản lý sinh viên
        Route::get('/students', [StudentController::class, 'index'])->name('students.index');
        Route::post('/students', [StudentController::class, 'store'])->name('students.store');
        Route::get('/students/{studentId}', [StudentController::class, 'show'])->name('students.show');
        Route::put('/students/{studentId}', [StudentController::class, 'update'])->name('students.update');
        Route::delete('/students/{studentId}', [StudentController::class, 'destroy'])->name('students.destroy');
        Route::get('/students/service-bills/{serviceBillId}/items', [StudentController::class, 'getServiceBillItems'])->name('students.service-bills.items');

        // Routes quản lý nhân viên
        Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
        Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
        Route::get('/staff/{staffId}', [StaffController::class, 'show'])->name('staff.show');
        Route::put('/staff/{staffId}', [StaffController::class, 'update'])->name('staff.update');
        Route::delete('/staff/{staffId}', [StaffController::class, 'destroy'])->name('staff.destroy');

        // Routes quản lý học kỳ
        Route::get('/semesters', [SemesterController::class, 'index'])->name('semesters.index');
        Route::post('/semesters', [SemesterController::class, 'store'])->name('semesters.store');
        Route::put('/semesters/{semesterId}', [SemesterController::class, 'update'])->name('semesters.update');
        Route::delete('/semesters/{semesterId}', [SemesterController::class, 'destroy'])->name('semesters.destroy');
        Route::get('/semesters/{semesterId}', [SemesterController::class, 'show'])->name('semesters.show');

        // Routes quản lý dịch vụ
        Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
        Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
        Route::put('/services/{serviceId}', [ServiceController::class, 'update'])->name('services.update');
        Route::delete('/services/{serviceId}', [ServiceController::class, 'destroy'])->name('services.destroy');

        // Routes quản lý giá tiện ích
        Route::get('/utility-rates', [UtilityRateController::class, 'index'])->name('utility_rates.index');
        Route::post('/utility-rates', [UtilityRateController::class, 'store'])->name('utility_rates.store');
        Route::put('/utility-rates/{rateId}', [UtilityRateController::class, 'update'])->name('utility_rates.update');
        Route::delete('/utility-rates/{rateId}', [UtilityRateController::class, 'destroy'])->name('utility_rates.destroy');

        // Routes quản lý feedback
        Route::get('/feedbacks', [FeedbackController::class, 'index'])->name('feedbacks.index');
        Route::put('/feedbacks/{feedbackId}', [FeedbackController::class, 'update'])->name('feedbacks.update');
        // Route::delete('/feedbacks/{feedbackId}', [FeedbackController::class, 'destroy'])->name('feedbacks.destroy');
        Route::post('/feedbacks/{feedbackId}/reject', [FeedbackController::class, 'rejectFeedback'])->name('feedbacks.reject');
        Route::get('/feedbacks/{feedbackId}', [FeedbackController::class, 'show'])->name('feedbacks.show');

        // Routes quản lý hoá đơn
        Route::get('/utility-bills', [UtilityBillController::class, 'index'])->name('utility_bills.index');
        Route::get('/service-bills', [ServiceBillController::class, 'index'])->name('service_bills.index');

        // Routes thống kê
        Route::get('/report', [ReportController::class, 'index'])->name('report.index');
        Route::get('/report/utilities', [ReportController::class, 'utilities'])->name('report.utilities');

        // Routes quản lý tài khoản
        Route::get('/profile', [StaffController::class, 'profile'])->name('staff.profile');
        Route::put('/profile/update', [StaffController::class, 'updateProfile'])->name('staff.profile.update');
        Route::get('/profile/password', [StaffController::class, 'showPasswordForm'])->name('staff.password.form');
        Route::put('/profile/password', [StaffController::class, 'updatePassword'])->name('staff.password.update');
    });
});
