@extends('layouts.admin')

@section('title', 'Quản lý Hợp Đồng')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Quản lý hợp đồng</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách hợp đồng</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Mã sinh viên</th>
                            <th>Tên sinh viên</th>
                            <th>Mã phòng</th>
                            <th>Học kỳ</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Mã sinh viên</th>
                            <th>Tên sinh viên</th>
                            <th>Mã phòng</th>
                            <th>Học kỳ</th>
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

    <!-- Include Modals -->
    @include('admin.contracts.approve')
    @include('admin.contracts.cancel')
    @include('admin.contracts.checkout')
    @include('admin.contracts.view')
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script>
    const contractsIndexUrl = '{{ route("contracts.index") }}';
    const contractsBaseUrl = '{{ url("/contracts") }}';
</script>
<script src="{{ asset('js/contracts.js') }}"></script>
@endpush