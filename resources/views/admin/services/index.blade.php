@extends('layouts.admin')

@section('title', 'Quản lý Dịch Vụ')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Quản lý dịch vụ</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách dịch vụ</h6>
            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addServiceModal">
                <i class="fas fa-plus fa-sm text-white-50"></i> Thêm dịch vụ
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tên dịch vụ</th>
                            <th>Giá (VNĐ)</th>
                            <th>Trạng thái</th>
                            <th>Hình ảnh</th> <!-- Thêm cột ảnh -->
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Tên dịch vụ</th>
                            <th>Giá (VNĐ)</th>
                            <th>Trạng thái</th>
                            <th>Hình ảnh</th> <!-- Thêm cột ảnh -->
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

    @include('admin.services.create')
    @include('admin.services.edit')
    @include('admin.services.delete')
@endsection

@push('styles')
<style>
    #dataTable td:nth-child(4) { /* Cột ảnh */
        width: 80px;
        height: 80px;
    }
</style>
@endpush

@push('scripts')
<script>
    const servicesIndexUrl = '{{ route("services.index") }}';
    const servicesBaseUrl = '{{ url("/services") }}';
    const servicesImageBaseUrl = '{{ asset("images/services") }}';
    const defaultImageUrl = '{{ asset("images/profiles/default_profile.jpg") }}';
</script>
<script src="{{ asset('js/services.js') }}"></script>
@endpush