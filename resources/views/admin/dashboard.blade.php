@extends('layouts.admin')

@section('title', 'Trang chủ Quản lý Ký Túc Xá')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Tổng quan</h1>
        </div>

        <!-- Content Row: Cards -->
        <div class="row">
            <!-- Tổng số phòng -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Tổng số phòng
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalRooms }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-door-open fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tổng số sinh viên -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Tổng số sinh viên
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalStudents }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hợp đồng đang chờ duyệt -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Hợp đồng chờ duyệt
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingContracts }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-file-contract fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Phản hồi chưa xử lý -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Phản hồi chưa xử lý
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingFeedbacks }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-comment fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row: Charts and Tables -->
        <div class="row">
            <!-- Biểu đồ số phòng theo trạng thái -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Tình trạng phòng</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-bar">
                            <canvas id="roomStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tổng quan tiện ích -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Tổng quan tiện ích</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4">
                            <canvas id="utilityChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row: Recent Contracts and Feedback -->
        <div class="row">
            <!-- Hợp đồng gần đây -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Hợp đồng gần đây</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Mã hợp đồng</th>
                                        <th>Sinh viên</th>
                                        <th>Phòng</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentContracts as $contract)
                                        <tr>
                                            <td>{{ $contract->contract_id }}</td>
                                            <td>{{ $contract->student->full_name }}</td>
                                            <td>{{ $contract->room->room_code ?? 'Chưa phân phòng' }}</td>
                                            <td>{{ $contract->status }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-info view-contract" data-id="{{ $contract->contract_id }}" data-toggle="modal" data-target="#viewContractModal">
                                                    <i class="fas fa-eye"></i> Xem
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Không có hợp đồng gần đây</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Phản hồi gần đây -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Phản hồi gần đây</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Mã phản hồi</th>
                                        <th>Sinh viên</th>
                                        <th>Phòng</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentFeedbacks as $feedback)
                                        <tr>
                                            <td>{{ $feedback->feedback_id }}</td>
                                            <td>{{ $feedback->student->full_name }}</td>
                                            <td>{{ $feedback->room->room_code }}</td>
                                            <td>{{ $feedback->status }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-info view-feedback" data-id="{{ $feedback->feedback_id }}" data-toggle="modal" data-target="#viewFeedbackModal">
                                                    <i class="fas fa-eye"></i> Xem
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Không có phản hồi gần đây</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.contracts.view');
    @include('admin.feedbacks.view');
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script>
        const feedbacksImageBaseUrl = '{{ asset("images/feedbacks") }}';
        const defaultProfileImageUrl = '{{ asset("images/profiles/default_profile.jpg") }}';
        // Biểu đồ trạng thái phòng
        var ctx = document.getElementById("roomStatusChart");
        var roomStatusChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Đang sử dụng", "Không sử dụng", "Đang sửa chữa"],
                datasets: [{
                    label: "Số phòng",
                    backgroundColor: ["#4e73df", "#1cc88a", "#f6c23e"],
                    data: [
                        {{ $roomStatus['Đang sử dụng'] ?? 0 }},
                        {{ $roomStatus['Không sử dụng'] ?? 0 }},
                        {{ $roomStatus['Đang sửa chữa'] ?? 0 }}
                    ],
                }]
            },
            options: {
                maintainAspectRatio: false,
                legend: { display: false },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Biểu đồ tiện ích
        var ctx = document.getElementById("utilityChart");
        var utilityChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ["Điện tiêu thụ (kWh)", "Nước tiêu thụ (m³)"],
                datasets: [{
                    data: [{{ $totalElectricityUsage }}, {{ $totalWaterUsage }}],
                    backgroundColor: ["#4e73df", "#1cc88a"],
                }]
            },
            options: {
                maintainAspectRatio: false,
                legend: { position: 'top' },
            }
        });

        // AJAX cho nút Xem hợp đồng và phản hồi
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.view-contract').on('click', function() {
                let id = $(this).data('id');
                $.ajax({
                    url: '/contracts/' + id,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            let contract = response.contract;
                            $('#view_student_code').text(contract.student.student_code || '');
                            $('#view_student_name').text(contract.student.full_name || '');
                            $('#view_room_code').text(contract.room ? contract.room.room_code : 'Chưa phân phòng');
                            $('#view_semester_name').text(contract.semester.semester_name || '');
                            $('#view_status').text(contract.status || '');
                            $('#view_contract_start_date').text(moment(contract.contract_start_date).format('DD-MM-YYYY') || '');
                            $('#view_contract_end_date').text(moment(contract.contract_end_date).format('DD-MM-YYYY') || '');
                            $('#view_contract_cost').text(new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(contract.contract_cost || 0));
                            $('#view_paid_amount').text(new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(contract.paid_amount || 0));
                            $('#viewContractModal').modal('show');
                        }
                    },
                    error: function(xhr) {
                        alert('Lỗi khi tải chi tiết hợp đồng: ' + xhr.responseJSON.message);
                    }
                });
            });

            // AJAX cho nút Xem phản hồi
            $('.view-feedback').on('click', function() {
                let id = $(this).data('id');
                $.ajax({
                    url: '/feedbacks/' + id,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            let feedback = response.feedback;
                            $('#view_room_code').text(feedback.room.room_code);
                            $('#view_student_name').text(feedback.student.full_name);
                            $('#view_content').text(feedback.content);
                            $('#view_quantity').text(feedback.quantity);
                            $('#view_status').text(feedback.status === 'pending' ? 'Chờ duyệt' : feedback.status === 'approved' ? 'Đã duyệt' : 'Từ chối');
                            $('#view_created_at').text(new Date(feedback.created_at).toLocaleDateString('vi-VN'));
                            $('#view_scheduled_fix_date').text(feedback.scheduled_fix_date ? new Date(feedback.scheduled_fix_date).toLocaleDateString('vi-VN') : 'Chưa có');
                            $('#view_staff_name').text(feedback.staff ? feedback.staff.full_name : 'Chưa có');
                            $('#view_image').attr('src', feedback.image ? `${feedbacksImageBaseUrl}/${feedback.image}` : defaultProfileImageUrl);

                            $('#viewFeedbackModal').modal('show');
                        }
                    },
                    error: function(xhr) {
                        alert('Lỗi khi tải chi tiết phản hồi: ' + xhr.responseJSON.message);
                    }
                });
            });
        });
    </script>
@endpush