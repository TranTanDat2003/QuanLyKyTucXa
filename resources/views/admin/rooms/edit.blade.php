<div class="modal fade" id="editRoomModal" tabindex="-1" role="dialog" aria-labelledby="editRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoomModalLabel">Chỉnh sửa phòng</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="editRoomForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="room_id" id="edit_room_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_room_code">Mã phòng</label>
                        <input type="text" name="room_code" id="edit_room_code" class="form-control">
                        <span class="text-danger" id="edit_room_code-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_building_id">Tòa nhà</label>
                        <select name="building_id" id="edit_building_id" class="form-control">
                            <option value="">Chọn tòa nhà</option>
                            @foreach($buildings as $building)
                                <option value="{{ $building->building_id }}">{{ $building->building_name }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger" id="edit_building_id-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_room_type_id">Loại phòng</label>
                        <select name="room_type_id" id="edit_room_type_id" class="form-control">
                            <option value="">Chọn loại phòng</option>
                            @foreach($roomTypes as $type)
                                <option value="{{ $type->room_type_id }}">{{ $type->room_type_name }} (Sức chứa: {{ $type->capacity }})</option>
                            @endforeach
                        </select>
                        <span class="text-danger" id="edit_room_type_id-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_status">Trạng thái</label>
                        <select name="status" id="edit_status" class="form-control">
                            <option value="Đang sử dụng">Đang sử dụng</option>
                            <option value="Không sử dụng">Không sử dụng</option>
                            <option value="Đang sửa chữa">Đang sửa chữa</option>
                        </select>
                        <span class="text-danger" id="edit_status-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_gender">Giới tính</label>
                        <select name="gender" id="edit_gender" class="form-control">
                            <option value="Nam">Nam</option>
                            <option value="Nữ">Nữ</option>
                        </select>
                        <span class="text-danger" id="edit_gender-error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>