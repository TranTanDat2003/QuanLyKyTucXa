@extends('layouts.admin')

@section('title', 'Quản lý Loại Phòng')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Quản lý loại phòng</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách loại phòng</h6>
            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addRoomTypeModal">
                <i class="fas fa-plus fa-sm text-white-50"></i> Thêm loại phòng
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tên loại phòng</th>
                            <th>Sức chứa</th>
                            <th>Giá</th>
                            <th>Máy lạnh</th>
                            <th>Nấu ăn</th>
                            <th>Hình ảnh</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Tên loại phòng</th>
                            <th>Sức chứa</th>
                            <th>Giá</th>
                            <th>Máy lạnh</th>
                            <th>Nấu ăn</th>
                            <th>Hình ảnh</th>
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

    <!-- Include Modal Thêm Loại Phòng -->
    @include('admin.room_types.create')

    <!-- Include Modal Xem Chi Tiết Loại Phòng -->
    @include('admin.room_types.view')

    <!-- Include Modal Chỉnh Sửa Loại Phòng -->
    @include('admin.room_types.edit')

    <!-- Include Modal Xoá Loại Phòng -->
    @include('admin.room_types.delete')
@endsection

@push('styles')
<style>
    #roomsTable th, #roomsTable td {
        text-align: center;
    }

    #dataTable td:nth-child(6) {
        width: 80px;
        height: 80px;
    }
</style>
@endpush

@push('scripts')
<script>
    // Truyền các biến từ Blade sang JS
    const roomTypesIndexUrl = '{{ route("room_types.index") }}';
    const roomTypesBaseUrl = '{{ url("/room-types") }}';
    const roomTypesImageBaseUrl = '{{ asset("images/room_types") }}';
    const defaultProfileImageUrl = '{{ asset("images/profiles/default_profile.jpg") }}';
</script>
<script src="{{ asset('js/room_types.js') }}"></script>
@endpush