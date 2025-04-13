@extends('layouts.admin')

@section('title', 'Thống kê Điện Nước')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Thống kê Điện Nước</h1>
    </div>

    <!-- Form chọn tháng -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Chọn tháng</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('report.utilities') }}">
                <div class="form-group">
                    <label for="month">Tháng</label>
                    <select name="month" id="month" class="form-control" onchange="this.form.submit()">
                        @foreach ($uniqueMonths as $month)
                            <option value="{{ $month }}" {{ $month == $selectedMonth ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::parse($month)->format('m/Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Biểu đồ thống kê -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Biểu đồ tiêu thụ điện nước - Tháng {{ \Carbon\Carbon::parse($selectedMonth)->format('m/Y') }}</h6>
        </div>
        <div class="card-body">
            <canvas id="utilityChart" height="100"></canvas>
        </div>
    </div>

    <!-- Bảng thống kê -->
    @foreach ($utilities as $building)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tòa nhà: {{ $building['building_name'] }} - Tháng {{ \Carbon\Carbon::parse($selectedMonth)->format('m/Y') }}</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable{{ $building['building_id'] }}" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Mã phòng</th>
                                <th>Điện (kWh)</th>
                                <th>Nước (m³)</th>
                                <th>Tổng chi phí (VND)</th>
                                <th>Trạng thái thanh toán</th>
                                <th>Chi tiết sinh viên</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($building['rooms'] as $utility)
                                <tr>
                                    <td>{{ $utility['room_code'] }}</td>
                                    <td>{{ number_format($utility['electricity_usage'], 2) }}</td>
                                    <td>{{ number_format($utility['water_usage'], 2) }}</td>
                                    <td>{{ number_format($utility['utility_cost'], 2) }}</td>
                                    <td>
                                        @if ($utility['is_fully_paid'])
                                            <span class="badge badge-success">Đã thanh toán đủ</span>
                                        @else
                                            <span class="badge badge-warning">Chưa thanh toán đủ</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (!$utility['is_fully_paid'])
                                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#studentModal{{ $utility['room_id'] }}">
                                                Xem chi tiết
                                            </button>

                                            <!-- Modal chi tiết sinh viên -->
                                            <div class="modal fade" id="studentModal{{ $utility['room_id'] }}" tabindex="-1" role="dialog" aria-labelledby="studentModalLabel{{ $utility['room_id'] }}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="studentModalLabel{{ $utility['room_id'] }}">Chi tiết thanh toán - Phòng {{ $utility['room_code'] }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>MSSV</th>
                                                                        <th>Họ tên</th>
                                                                        <th>Số tiền phải trả</th>
                                                                        <th>Số tiền đã trả</th>
                                                                        <th>Trạng thái</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($utility['students'] as $student)
                                                                        <tr>
                                                                            <td>{{ $student['student_code'] }}</td>
                                                                            <td>{{ $student['full_name'] }}</td>
                                                                            <td>{{ number_format($student['share_amount'], 2) }}</td>
                                                                            <td>{{ number_format($student['amount_paid'], 2) }}</td>
                                                                            <td>
                                                                                @if ($student['is_paid'])
                                                                                    <span class="badge badge-success">Đã thanh toán</span>
                                                                                @else
                                                                                    <span class="badge badge-danger">Chưa thanh toán</span>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            Không cần chi tiết
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Khởi tạo DataTable cho từng tòa nhà
        @foreach ($utilities as $building)
            $('#dataTable{{ $building['building_id'] }}').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Vietnamese.json"
                }
            });
        @endforeach

        // Khởi tạo biểu đồ Chart.js
        const ctx = document.getElementById('utilityChart').getContext('2d');
        const chartData = @json($chartData);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'Điện trung bình (kWh)',
                        data: chartData.electricity_usage,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Nước trung bình (m³)',
                        data: chartData.water_usage,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Tổng chi phí (VND)',
                        data: chartData.utility_cost,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        yAxisID: 'y-cost'
                    },
                    {
                        label: 'Đã đóng (VND)',
                        data: chartData.amount_paid,
                        backgroundColor: 'rgba(153, 102, 255, 0.5)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1,
                        yAxisID: 'y-cost'
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Tiêu thụ trung bình (kWh, m³)'
                        }
                    },
                    'y-cost': {
                        beginAtZero: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Tiền (VND)'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tòa nhà'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.dataset.label === 'Tổng chi phí (VND)' || context.dataset.label === 'Đã đóng (VND)') {
                                    label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                                } else {
                                    label += context.parsed.y.toFixed(2);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush