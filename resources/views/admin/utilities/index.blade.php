@extends('layouts.admin')

@section('title', 'Quản lý Tiện ích')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Quản lý tiện ích</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách phòng</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Mã phòng</th>
                            <th>Loại phòng</th>
                            <th>Tên tòa nhà</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Mã phòng</th>
                            <th>Loại phòng</th>
                            <th>Tên tòa nhà</th>
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

    @include('admin.utilities.create')
    @include('admin.utilities.view')
    @include('admin.utilities.edit')
@endsection

@push('scripts')
<script>
    const utilitiesIndexUrl = '{{ route("utilities.index") }}';
    const utilitiesBaseUrl = '{{ url("/utilities") }}';
</script>
<script src="{{ asset('js/utilities.js') }}"></script>
@endpush