@extends('layouts.admin')

@section('title', 'Quản lý Tòa Nhà')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Quản lý tòa nhà</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách tòa nhà</h6>
            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addBuildingModal">
                <i class="fas fa-plus fa-sm text-white-50"></i> Thêm tòa nhà
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tên tòa nhà</th>
                            <th>Mô tả</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Tên tòa nhà</th>
                            <th>Mô tả</th>
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

    <!-- Include Modal Thêm Toà Nhà -->
    @include('admin.buildings.create')

    <!-- Include Modal Chỉnh Sửa Toà Nhà -->
    @include('admin.buildings.edit')
    
    <!-- Include Modal Xoá Toà Nhà -->
    @include('admin.buildings.delete')
@endsection

@push('scripts')
<script>
    const buildingsIndexUrl = '{{ route("buildings.index") }}';
    const buildingsBaseUrl = '{{ url("/buildings") }}';
</script>
<script src="{{ asset('js/buildings.js') }}"></script>
@endpush