@extends('layouts.admin')

@section('title', 'Quản lý Sinh Viên')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Quản lý sinh viên</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách sinh viên</h6>
            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addStudentModal">
                <i class="fas fa-plus fa-sm text-white-50"></i> Thêm sinh viên
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Mã sinh viên</th>
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
                            <th>Mã sinh viên</th>
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

    <!-- Include Modal Thêm Sinh Viên -->
    @include('admin.students.create')

    <!-- Include Modal Xem Chi Tiết Sinh Viên -->
    @include('admin.students.view')

    <!-- Include Modal Chỉnh Sửa Sinh Viên -->
    @include('admin.students.edit')

    <!-- Include Modal Xoá Sinh Viên -->
    @include('admin.students.delete')
@endsection

@push('styles')
<style>
    #dataTable td:nth-child(6) {
        text-align: center;
    }

    .badge-status {
        width: 80px;  /* Độ rộng cố định */
    }
</style>
@endpush

@push('scripts')
<script>
    const studentsIndexUrl = '{{ route("students.index") }}';
    const studentsBaseUrl = '{{ url("/students") }}';
    const studentsImageBaseUrl = '{{ asset("images/profiles/students") }}';
    const defaultProfileImageUrl = '{{ asset("images/profiles/default_profile.jpg") }}';
</script>
<script src="{{ asset('js/students.js') }}"></script>
@endpush