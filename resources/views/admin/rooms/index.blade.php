@extends('layouts.admin')

@section('title', 'Quản lý Phòng')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Quản lý phòng</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách phòng</h6>
            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addRoomModal">
                <i class="fas fa-plus fa-sm text-white-50"></i> Thêm phòng
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Mã phòng</th>
                            <th>Tòa nhà</th>
                            <th>Loại phòng</th>
                            <th>Số chỗ trống</th>
                            <th>Trạng thái</th>
                            <th>Giới tính</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Mã phòng</th>
                            <th>Tòa nhà</th>
                            <th>Loại phòng</th>
                            <th>Số chỗ trống</th>
                            <th>Trạng thái</th>
                            <th>Giới tính</th>
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

    <!-- Include Modal Thêm Phòng -->
    @include('admin.rooms.create')

    <!-- Include Modal Chỉnh Sửa Phòng -->
    @include('admin.rooms.edit')

    <!-- Include Modal Xoá Phòng -->
    @include('admin.rooms.delete')
@endsection

@push('scripts')
<script>
    // Truyền các biến từ Blade sang JS
    const roomsIndexUrl = '{{ route("rooms.index") }}';
    const roomsBaseUrl = '{{ url("/rooms") }}';
</script>
<script src="{{ asset('js/rooms.js') }}"></script>
@endpush