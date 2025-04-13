<div class="modal fade" id="addServiceModal" tabindex="-1" role="dialog" aria-labelledby="addServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addServiceModalLabel">Thêm dịch vụ mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="POST" action="{{ route('services.store') }}" id="addServiceForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="service_name">Tên dịch vụ</label>
                        <input type="text" name="service_name" id="service_name" class="form-control" value="{{ old('service_name') }}">
                        <span class="text-danger" id="add_service_name-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="price">Giá (VNĐ)</label>
                        <input type="number" name="price" id="price" class="form-control" min="1000" step="1000" value="{{ old('price') }}">
                        <span class="text-danger" id="add_price-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="service_img">Hình ảnh</label>
                        <div class="row">
                            <div class="col-2">
                                <img class="preview_img" src="{{ asset('images/profiles/default_profile.jpg') }}">
                            </div>
                            <div class="col-10">
                                <div class="file_upload text-secondary">
                                    <input type="file" class="file_upload_input" name="service_img" id="service_img" accept="image/*">
                                    <span class="fs-4 fw-2 file_upload_label">Chọn ảnh</span>
                                    <span>Hoặc kéo và thả ảnh ở đây</span>
                                </div>
                            </div>
                        </div>
                        <span class="text-danger" id="add_service_img-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="service_description">Mô tả dịch vụ</label>
                        <textarea name="service_description" id="service_description" class="form-control" rows="3">{{ old('service_description') }}</textarea>
                        <span class="text-danger" id="add_service_description-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="is_active">Hoạt động</label>
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary" id="addServiceBtn">Thêm dịch vụ</button>
                </div>
            </form>
        </div>
    </div>
</div>