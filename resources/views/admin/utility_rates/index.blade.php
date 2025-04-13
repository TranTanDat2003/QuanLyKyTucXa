@extends('layouts.admin')

@section('title', 'Quản lý Giá Tiện Ích')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Quản lý giá tiện ích</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách giá tiện ích</h6>
            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addUtilityRateModal">
                <i class="fas fa-plus fa-sm text-white-50"></i> Thêm giá tiện ích
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Giá điện (VNĐ)</th>
                            <th>Giá nước (VNĐ)</th>
                            <th>Ngày hiệu lực</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Giá điện (VNĐ)</th>
                            <th>Giá nước (VNĐ)</th>
                            <th>Ngày hiệu lực</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <!-- Dữ liệu sẽ được tải bằng AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('admin.utility_rates.create')
    @include('admin.utility_rates.edit')
    @include('admin.utility_rates.delete')
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script>
    const utilityRatesIndexUrl = '{{ route("utility_rates.index") }}';
    const utilityRatesBaseUrl = '{{ url("/utility-rates") }}';
</script>
<script src="{{ asset('js/utility_rates.js') }}"></script>
@endpush