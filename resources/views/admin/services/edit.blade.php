<div class="modal fade" id="editServiceModal" tabindex="-1" role="dialog" aria-labelledby="editServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editServiceModalLabel">Chỉnh sửa dịch vụ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="POST" id="editServiceForm" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <input type="hidden" name="service_id" id="edit_service_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_service_name">Tên dịch vụ</label>
                        <input type="text" name="service_name" id="edit_service_name" class="form-control" value="{{ old('service_name') }}">
                        <span class="text-danger" id="edit_service_name-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_price">Giá (VNĐ)</label>
                        <input type="number" name="price" id="edit_price" class="form-control" min="1000" step="1000" value="{{ old('price') }}">
                        <span class="text-danger" id="edit_price-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_service_img">Hình ảnh</label>
                        <div class="row">
                            <div class="col-2">
                                <img class="preview_img" src="{{ asset('images/profiles/default_profile.jpg') }}">
                            </div>
                            <div class="col-10">
                                <div class="file_upload text-secondary">
                                    <input type="file" class="file_upload_input" name="service_img" id="edit_service_img" accept="image/*">
                                    <input type="hidden" name="image_old" id="image_old">
                                    <span class="fs-4 fw-2 file_upload_label">Chọn ảnh</span>
                                    <span>Hoặc kéo và thả ảnh ở đây</span>
                                </div>
                            </div>
                        </div>
                        <span class="text-danger" id="edit_service_img-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_service_description">Mô tả dịch vụ</label>
                        <textarea name="service_description" id="edit_service_description" class="form-control" rows="3">{{ old('service_description') }}</textarea>
                        <span class="text-danger" id="edit_service_description-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_is_active">Hoạt động</label>
                        <input type="checkbox" name="is_active" id="edit_is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary" id="editServiceBtn">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>