<div class="modal fade" id="editUtilityModal" tabindex="-1" role="dialog" aria-labelledby="editUtilityModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUtilityModalLabel">Chỉnh sửa giá điện nước tháng <span id="edit_month_title"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="POST" id="editUtilityForm">
                @method('PUT')
                @csrf
                <input type="hidden" name="utility_id" id="edit_utility_id">
                <input type="hidden" name="room_id" id="edit_room_id">
                <input type="hidden" name="rate_id" id="edit_rate_id">
                <input type="hidden" name="month" id="edit_month">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold text-primary">Mã phòng:</label>
                        <p id="edit_room_code"></p>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-6">
                            <label class="font-weight-bold text-primary">Đơn giá điện (VNĐ/kWh):</label>
                            <p id="edit_electricity_rate"></p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            <label class="font-weight-bold text-primary">Đơn giá nước (VNĐ/m³):</label>
                            <p id="edit_water_rate"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-6">
                            <label for="edit_electricity_reading" class="font-weight-bold text-primary">Chỉ số điện (kWh):</label>
                            <input type="number" name="electricity_reading" id="edit_electricity_reading" class="form-control" min="0" step="1" value="{{ old('electricity_reading') }}">
                            <span class="text-danger" id="edit_electricity_reading-error"></span>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            <label for="edit_water_reading" class="font-weight-bold text-primary">Chỉ số nước (m³):</label>
                            <input type="number" name="water_reading" id="edit_water_reading" class="form-control" min="0" step="1" value="{{ old('water_reading') }}">
                            <span class="text-danger" id="edit_water_reading-error"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary" id="editUtilityBtn">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>