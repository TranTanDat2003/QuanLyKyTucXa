<div class="modal fade" id="addRoomModal" tabindex="-1" role="dialog" aria-labelledby="addRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRoomModalLabel">Thêm phòng mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('rooms.store') }}" id="addRoomForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="room_code">Mã phòng</label>
                        <input type="text" name="room_code" id="room_code" class="form-control" value="{{ old('room_code') }}">
                        <span class="text-danger" id="add_room_code-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="building_id">Tòa nhà</label>
                        <select name="building_id" id="building_id" class="form-control">
                            <option value="">Chọn tòa nhà</option>
                            @foreach($buildings as $building)
                                <option value="{{ $building->building_id }}">{{ $building->building_name }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger" id="add_building_id-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="room_type_id">Loại phòng</label>
                        <select name="room_type_id" id="room_type_id" class="form-control">
                            <option value="">Chọn loại phòng</option>
                            @foreach($roomTypes as $type)
                                <option value="{{ $type->room_type_id }}">{{ $type->room_type_name }} (Sức chứa: {{ $type->capacity }})</option>
                            @endforeach
                        </select>
                        <span class="text-danger" id="add_room_type_id-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="status">Trạng thái</label>
                        <select name="status" id="status" class="form-control">
                            <option value="Đang sử dụng">Đang sử dụng</option>
                            <option value="Không sử dụng">Không sử dụng</option>
                            <option value="Đang sửa chữa">Đang sửa chữa</option>
                        </select>
                        <span class="text-danger" id="add_status-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="gender">Giới tính</label>
                        <select name="gender" id="gender" class="form-control">
                            <option value="Nam">Nam</option>
                            <option value="Nữ">Nữ</option>
                        </select>
                        <span class="text-danger" id="add_gender-error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm phòng</button>
                </div>
            </form>
        </div>
    </div>
</div>