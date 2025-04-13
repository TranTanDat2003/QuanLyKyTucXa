<div class="modal fade" id="editStaffModal" tabindex="-1" role="dialog" aria-labelledby="editStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStaffModalLabel">Chỉnh sửa nhân viên</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="POST" id="editStaffForm" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <input type="hidden" name="staff_id" id="edit_staff_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_full_name">Họ tên</label>
                        <input type="text" name="full_name" id="edit_full_name" class="form-control">
                        <span class="text-danger" id="edit_full_name-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_date_of_birth">Ngày sinh</label>
                        <input type="date" name="date_of_birth" id="edit_date_of_birth" class="form-control">
                        <span class="text-danger" id="edit_date_of_birth-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_gender">Giới tính</label>
                        <select name="gender" id="edit_gender" class="form-control">
                            <option value="0">Nam</option>
                            <option value="1">Nữ</option>
                        </select>
                        <span class="text-danger" id="edit_gender-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_phone">Số điện thoại</label>
                        <input type="text" name="phone" id="edit_phone" class="form-control">
                        <span class="text-danger" id="edit_phone-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_address">Địa chỉ</label>
                        <input type="text" name="address" id="edit_address" class="form-control">
                        <span class="text-danger" id="edit_address-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_email">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control">
                        <span class="text-danger" id="edit_email-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_image">Hình ảnh</label>
                        <div class="row">
                            <div class="col-2">
                                <img class="preview_img" src="{{ asset('images/profiles/default_profile.jpg') }}">
                            </div>
                            <div class="col-10">
                                <div class="file_upload text-secondary">
                                    <input type="file" class="file_upload_input" name="image" id="edit_image" accept="image/*">
                                    <input type="hidden" name="image_old" id="image_old">
                                    <span class="fs-4 fw-2 file_upload_label">Chọn ảnh</span>
                                    <span>Hoặc kéo và thả ảnh ở đây</span>
                                </div>
                            </div>
                        </div>
                        <span class="text-danger" id="edit_image-error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary" id="editStaffBtn">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>