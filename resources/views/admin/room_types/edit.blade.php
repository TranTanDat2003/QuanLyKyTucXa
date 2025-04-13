<!-- Modal Chỉnh Sửa Loại Phòng -->
<div class="modal fade" id="editRoomTypeModal" tabindex="-1" role="dialog" aria-labelledby="editRoomTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoomTypeModalLabel">Chỉnh sửa loại phòng</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="editRoomTypeForm" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <input type="hidden" name="room_type_id" id="edit_room_type_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_room_type_name">Tên loại phòng</label>
                        <input type="text" name="room_type_name" id="edit_room_type_name" class="form-control" value="{{ old('room_type_name') }}">
                        <span class="text-danger" id="edit_room_type_name-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_capacity">Sức chứa</label>
                        <input type="number" name="capacity" id="edit_capacity" class="form-control" min="2" max="8" value="{{ old('capacity') }}">
                        <span class="text-danger" id="edit_capacity-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_room_type_price">Giá (VNĐ)</label>
                        <input type="number" name="room_type_price" id="edit_room_type_price" class="form-control" min="100000" step="1000" value="{{ old('room_type_price') }}">
                        <span class="text-danger" id="edit_room_type_price-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_room_type_img">Hình ảnh</label>
                        <div class="row">
                            <div class="col-2">
                                <img class="preview_img" src="{{ asset('images/profiles/default_profile.jpg') }}">
                            </div>
                            <div class="col-10">
                                <div class="file_upload text-secondary">
                                    <input type="file" class="file_upload_input" name="room_type_img" id="edit_room_type_img" accept="image/*">
                                    <input type="hidden" name="image_old" id="image_old">
                                    <span class="fs-4 fw-2 file_upload_label">Chọn ảnh</span>
                                    <span>Hoặc kéo và thả ảnh ở đây</span>
                                </div>
                            </div>
                        </div>
                        <span class="text-danger" id="edit_room_type_img-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_has_air_conditioner">Có máy lạnh</label>
                        <input type="checkbox" name="has_air_conditioner" id="edit_has_air_conditioner" value="1" {{ old('has_air_conditioner') ? 'checked' : '' }}>
                    </div>
                    <div class="form-group">
                        <label for="edit_allow_cooking">Cho phép nấu ăn</label>
                        <input type="checkbox" name="allow_cooking" id="edit_allow_cooking" value="1" {{ old('allow_cooking') ? 'checked' : '' }}>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary" id="editRoomTypeBtn">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>