@extends('layouts.admin')

@section('title', 'Đổi mật khẩu')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Đổi mật khẩu</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Cập nhật mật khẩu</h6>
        </div>
        <div class="card-body">
            <form id="passwordForm">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="current_password">Mật khẩu hiện tại</label>
                    <input type="password" name="current_password" id="current_password" class="form-control">
                    <span class="text-danger" id="current_password-error"></span>
                </div>
                <div class="form-group">
                    <label for="new_password">Mật khẩu mới</label>
                    <input type="password" name="new_password" id="new_password" class="form-control">
                    <span class="text-danger" id="new_password-error"></span>
                </div>
                <div class="form-group">
                    <label for="new_password_confirmation">Xác nhận mật khẩu mới</label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control">
                    <span class="text-danger" id="new_password_confirmation-error"></span>
                </div>
                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary" id="updatePasswordBtn">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Handle form submission
        $('#passwordForm').on('submit', function(e) {
            e.preventDefault();

            // Xóa các lỗi cũ trước khi gửi yêu cầu
            $('#current_password-error').text('');
            $('#new_password-error').text('');
            $('#new_password_confirmation-error').text('');

            let formData = new FormData(this);

            $.ajax({
                url: '{{ route("staff.password.update") }}',
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#passwordForm')[0].reset();
                        $('#current_password-error').text('');
                        $('#new_password-error').text('');
                        $('#new_password_confirmation-error').text('');
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $(`#${key}-error`).text(value[0]);
                        });
                    } else {
                        toastr.error('Có lỗi xảy ra khi đổi mật khẩu');
                    }
                }
            });
        });
    });
</script>
@endpush