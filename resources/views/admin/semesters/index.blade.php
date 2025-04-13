@extends('layouts.admin')

@section('title', 'Quản lý Học Kỳ')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Quản lý học kỳ</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách học kỳ</h6>
            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addSemesterModal">
                <i class="fas fa-plus fa-sm text-white-50"></i> Thêm học kỳ
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tên học kỳ</th>
                            <th>Năm học</th>
                            <th>Ngày bắt đầu</th>
                            <th>Ngày kết thúc</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Tên học kỳ</th>
                            <th>Năm học</th>
                            <th>Ngày bắt đầu</th>
                            <th>Ngày kết thúc</th>
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

    @include('admin.semesters.create')
    @include('admin.semesters.view')
    @include('admin.semesters.edit')
    @include('admin.semesters.delete')
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script>
    const semestersIndexUrl = '{{ route("semesters.index") }}';
    const semestersBaseUrl = '{{ url("/semesters") }}';
</script>
<script src="{{ asset('js/semesters.js') }}"></script>
@endpush