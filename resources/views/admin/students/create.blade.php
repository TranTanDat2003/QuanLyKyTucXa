<div class="modal fade" id="addStudentModal" tabindex="-1" role="dialog" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStudentModalLabel">Tạo tài khoản sinh viên</h5>
                <button type="button" class="close" data-dismiss="modal" aria="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('students.store') }}" id="addStudentForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="full_name">Họ tên <span class="required-star">*</span></label>
                        <input type="text" name="full_name" id="full_name" class="form-control" value="{{ old('full_name') }}">
                        <span class="text-danger" id="add_full_name-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="date_of_birth">Ngày sinh <span class="required-star">*</span></label>
                        <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
                        <span class="text-danger" id="add_date_of_birth-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="gender">Giới tính <span class="required-star">*</span></label>
                        <select name="gender" id="gender" class="form-control">
                            <option value="0" {{ old('gender') == '0' ? 'selected' : '' }}>Nam</option>
                            <option value="1" {{ old('gender') == '1' ? 'selected' : '' }}>Nữ</option>
                        </select>
                        <span class="text-danger" id="add_gender-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="phone">Số điện thoại</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
                        <span class="text-danger" id="add_phone-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="address">Địa chỉ</label>
                        <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}">
                        <span class="text-danger" id="add_address-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="email">Email <span class="required-star">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
                        <span class="text-danger" id="add_email-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="major">Ngành học</label>
                        <input type="text" name="major" id="major" class="form-control" value="{{ old('major') }}">
                        <span class="text-danger" id="add_major-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="class">Lớp</label>
                        <input type="text" name="class" id="class" class="form-control" value="{{ old('class') }}">
                        <span class="text-danger" id="add_class-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="enrollment_year">Năm nhập học <span class="required-star">*</span></label>
                        <input type="number" name="enrollment_year" id="enrollment_year" class="form-control" min="2000" max="{{ date('Y') }}" value="{{ old('enrollment_year') }}">
                        <span class="text-danger" id="add_enrollment_year-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="image">Hình ảnh</label>
                        <div class="row">
                            <div class="col-2">
                                <img class="preview_img" src="{{ asset('images/profiles/default_profile.jpg') }}">
                            </div>
                            <div class="col-10">
                                <div class="file_upload text-secondary">
                                    <input type="file" class="file_upload_input" name="image" id="image" accept="image/*">
                                    <span class="fs-4 fw-2 file_upload_label">Chọn ảnh</span>
                                    <span>Hoặc kéo và thả ảnh ở đây</span>
                                </div>
                            </div>
                        </div>
                        <span class="text-danger" id="add_image-error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary" id="addStudentBtn">Tạo tài khoản</button>
                </div>
            </form>
        </div>
    </div>
</div>