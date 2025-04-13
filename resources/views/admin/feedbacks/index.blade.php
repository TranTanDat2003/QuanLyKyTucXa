@extends('layouts.admin')

@section('title', 'Quản lý Báo cáo Lỗi')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Quản lý báo cáo lỗi</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách báo cáo lỗi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Mã phòng</th>
                            <th>Sinh viên</th>
                            <th>Nội dung</th>
                            <th>Số lượng lỗi</th>
                            <th>Trạng thái</th>
                            <th>Ảnh</th>
                            <th>Ngày gửi</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dữ liệu sẽ được tải bằng AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('admin.feedbacks.view')
    @include('admin.feedbacks.edit')
    @include('admin.feedbacks.delete')
@endsection

@push('styles')
    <style>
        #dataTable td:nth-child(6) {
            width: 80px;
            height: 80px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const feedbacksIndexUrl = '{{ route("feedbacks.index") }}';
        const feedbacksBaseUrl = '{{ url("/feedbacks") }}';
        const feedbacksImageBaseUrl = '{{ asset("images/feedbacks") }}';
        const defaultProfileImageUrl = '{{ asset("images/profiles/default_profile.jpg") }}';
    </script>
    <script src="{{ asset('js/feedbacks.js') }}"></script>
@endpush