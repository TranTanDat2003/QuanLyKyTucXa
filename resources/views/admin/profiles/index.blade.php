@extends('layouts.admin')

@section('title', 'Thông tin cá nhân')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Thông tin cá nhân</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Cập nhật thông tin cá nhân</h6>
        </div>
        <div class="card-body">
            <form id="profileForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="image">Ảnh đại diện</label>
                            <div class="row">
                                <div class="col-12 text-center">
                                    <img class="preview_img_profile img-fluid" src="{{ $staff->image === 'default_profile.jpg' ? asset('images/profiles/default_profile.jpg') : asset('images/profiles/staff/' . $staff->image) }}" alt="Ảnh đại diện">
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="file_upload text-secondary">
                                        <input type="file" class="file_upload_input" name="image" id="image" accept="image/*">
                                        <span class="fs-4 fw-2 file_upload_label">Chọn ảnh</span>
                                        <span>Hoặc kéo và thả ảnh ở đây</span>
                                    </div>
                                    <span class="text-danger" id="image-error"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="full_name">Họ và tên</label>
                            <input type="text" name="full_name" id="full_name" class="form-control" value="{{ $staff->full_name }}">
                            <span class="text-danger" id="full_name-error"></span>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ $staff->email }}">
                            <span class="text-danger" id="email-error"></span>
                        </div>
                        <div class="form-group">
                            <label for="phone">Số điện thoại</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ $staff->phone }}">
                            <span class="text-danger" id="phone-error"></span>
                        </div>
                        <div class="form-group">
                            <label for="date_of_birth">Ngày sinh</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="{{ $staff->date_of_birth ? \Carbon\Carbon::createFromFormat('d/m/Y', $staff->date_of_birth)->format('Y-m-d') : '' }}">
                            <span class="text-danger" id="date_of_birth-error"></span>
                        </div>
                        <div class="form-group">
                            <label for="gender">Giới tính</label>
                            <select name="gender" id="gender" class="form-control">
                                <option value="0" {{ $staff->gender == 0 ? 'selected' : '' }}>Nam</option>
                                <option value="1" {{ $staff->gender == 1 ? 'selected' : '' }}>Nữ</option>
                            </select>
                            <span class="text-danger" id="gender-error"></span>
                        </div>
                        <div class="form-group">
                            <label for="address">Địa chỉ</label>
                            <input type="text" name="address" id="address" class="form-control" value="{{ $staff->address }}">
                            <span class="text-danger" id="address-error"></span>
                        </div>
                    </div>
                </div>
                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary" id="updateProfileBtn">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .preview_img_profile {
        width: 250px;
        height: 250px;
        border-radius: 100%;
        object-fit: cover;
        border: 4px solid silver;
    }
    .file_upload {
        border: 1px dashed #ccc;
        padding: 10px;
        text-align: center;
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Preview image when file is selected
        $('#image').on('change', function() {
            let file = this.files[0];
            if (file) {
                let url = URL.createObjectURL(file);
                $(this).closest('.form-group').find('.preview_img').attr('src', url);
            }
        });

        // Handle form submission
        $('#profileForm').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: '{{ route("staff.profile.update") }}',
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
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
                        toastr.error('Có lỗi xảy ra khi cập nhật thông tin cá nhân');
                    }
                }
            });
        });
    });
</script>
@endpush