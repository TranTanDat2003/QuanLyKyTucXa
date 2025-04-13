<!-- resources/views/admin/reports/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Thống kê Ký Túc Xá')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Thống kê Ký Túc Xá</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Tổng số sinh viên -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tổng số sinh viên</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_students }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tổng số nhân viên -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tổng số nhân viên</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_staff }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tổng số tòa nhà -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tổng số tòa nhà</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_buildings }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tổng số phòng -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Tổng số phòng</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_rooms }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-door-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Phòng trống -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Phòng trống</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $available_rooms }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-door-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tổng số hợp đồng -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tổng số hợp đồng</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_contracts }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-contract fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hợp đồng đang ở -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Hợp đồng đang ở</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $active_contracts }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-contract fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hợp đồng chờ duyệt -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Hợp đồng chờ duyệt</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pending_contracts }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-contract fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tổng số hóa đơn dịch vụ -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tổng số hóa đơn dịch vụ</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_service_bills }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hóa đơn dịch vụ chưa thanh toán -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Hóa đơn dịch vụ chưa thanh toán</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pending_service_bills }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tổng số hóa đơn tiện ích -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tổng số hóa đơn tiện ích</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_utility_bills }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hóa đơn tiện ích chưa thanh toán -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Hóa đơn tiện ích chưa thanh toán</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $unpaid_utility_bills }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tổng số phản hồi -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tổng số phản hồi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_feedback }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comment fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Phản hồi chờ xử lý -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Phản hồi chờ xử lý</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pending_feedback }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comment fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ -->
    <div class="row">
        <!-- Biểu đồ hợp đồng -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tỷ lệ hợp đồng theo trạng thái</h6>
                </div>
                <div class="card-body">
                    <canvas id="contractChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Biểu đồ tỷ lệ phòng -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tỷ lệ phòng</h6>
                </div>
                <div class="card-body">
                    <canvas id="roomChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ doanh thu -->
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Doanh thu theo học kỳ</h6>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Biểu đồ phản hồi -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Phản hồi theo trạng thái</h6>
                </div>
                <div class="card-body">
                    <canvas id="feedbackChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Biểu đồ sử dụng điện/nước -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Mức sử dụng điện/nước trung bình theo tháng</h6>
                </div>
                <div class="card-body">
                    <canvas id="utilityChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Biểu đồ hợp đồng (biểu đồ tròn)
    const contractCtx = document.getElementById('contractChart').getContext('2d');
    const contractChart = new Chart(contractCtx, {
        type: 'pie',
        data: {
            labels: ['Chờ duyệt', 'Đã duyệt', 'Đang ở', 'Hết hạn', 'Hủy'],
            datasets: [{
                label: 'Tỷ lệ hợp đồng',
                data: [
                    {{ $contract_stats['pending'] }},
                    {{ $contract_stats['approved'] }},
                    {{ $contract_stats['active'] }},
                    {{ $contract_stats['expired'] }},
                    {{ $contract_stats['canceled'] }}
                ],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(153, 102, 255, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    // Biểu đồ tỷ lệ phòng
    const roomCtx = document.getElementById('roomChart').getContext('2d');
    const roomChart = new Chart(roomCtx, {
        type: 'pie',
        data: {
            labels: ['Phòng trống', 'Phòng đã đầy'],
            datasets: [{
                data: [{{ $room_stats['available'] }}, {{ $room_stats['occupied'] }}],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(255, 99, 132, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    // Biểu đồ phản hồi (biểu đồ cột)
    const feedbackCtx = document.getElementById('feedbackChart').getContext('2d');
    const feedbackChart = new Chart(feedbackCtx, {
        type: 'bar',
        data: {
            labels: ['Chờ duyệt', 'Đã duyệt', 'Từ chối'],
            datasets: [{
                label: 'Số lượng phản hồi',
                data: [
                    {{ $feedback_stats['pending'] }},
                    {{ $feedback_stats['approved'] }},
                    {{ $feedback_stats['rejected'] }}
                ],
                backgroundColor: [
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderColor: [
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Số lượng'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Trạng thái'
                    }
                }
            }
        }
    });

    // Biểu đồ sử dụng điện/nước trung bình (biểu đồ đường)
    const utilityCtx = document.getElementById('utilityChart').getContext('2d');
    const utilityChart = new Chart(utilityCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($utility_stats['labels']) !!},
            datasets: [
                {
                    label: 'Điện trung bình (kWh)',
                    data: {!! json_encode($utility_stats['avg_electricity']) !!},
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Nước trung bình (m³)',
                    data: {!! json_encode($utility_stats['avg_water']) !!},
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Mức sử dụng'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Tháng'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    // Biểu đồ doanh thu theo học kỳ
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($revenue_stats['labels']) !!},
            datasets: [
                {
                    label: 'Doanh thu hợp đồng',
                    data: {!! json_encode($revenue_stats['contract_revenue']) !!},
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Doanh thu dịch vụ',
                    data: {!! json_encode($revenue_stats['service_revenue']) !!},
                    borderColor: 'rgba(255, 206, 86, 1)',
                    backgroundColor: 'rgba(255, 206, 86, 0.2)',
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Doanh thu (VND)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Học kỳ'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
</script>
@endpush