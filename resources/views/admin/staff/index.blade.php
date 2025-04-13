@extends('layouts.admin')

@section('title', 'Quản lý Nhân Viên')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Quản lý nhân viên</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách nhân viên</h6>
            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addStaffModal">
                <i class="fas fa-plus fa-sm text-white-50"></i> Thêm nhân viên
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Mã nhân viên</th>
                            <th>Họ tên</th>
                            <th>Ngày sinh</th>
                            <th>Giới tính</th>
                            <th>Email</th>
                            <th>Trạng thái tài khoản</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Mã nhân viên</th>
                            <th>Họ tên</th>
                            <th>Ngày sinh</th>
                            <th>Giới tính</th>
                            <th>Email</th>
                            <th>Trạng thái tài khoản</th>
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

    @include('admin.staff.create')
    @include('admin.staff.view')
    @include('admin.staff.edit')
    @include('admin.staff.delete')
@endsection

@push('styles')
<style>
    #dataTable td:nth-child(6) {
        text-align: center;
    }
    .badge-status {
        width: 80px;
    }
</style>
@endpush

@push('scripts')
<script>
    const staffIndexUrl = '{{ route("staff.index") }}';
    const staffBaseUrl = '{{ url("/staff") }}';
    const staffImageBaseUrl = '{{ asset("images/profiles/staff") }}';
    const defaultProfileImageUrl = '{{ asset("images/profiles/default_profile.jpg") }}';
</script>
<script src="{{ asset('js/staff.js') }}"></script>

@endpush