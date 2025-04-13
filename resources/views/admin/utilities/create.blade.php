<div class="modal fade" id="addUtilityModal" tabindex="-1" role="dialog" aria-labelledby="addUtilityModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUtilityModalLabel">Thêm tiện ích mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="POST" action="{{ route('utilities.store') }}" id="addUtilityForm">
                @csrf
                <input type="hidden" name="room_id" id="add_room_id">
                <input type="hidden" name="rate_id" id="add_rate_id">
                <input type="hidden" name="month" id="month" value="{{ now()->startOfMonth()->toDateString() }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-6">
                            <label class="font-weight-bold text-primary">Mã phòng:</label>
                            <p id="add_room_code"></p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            <label class="font-weight-bold text-primary">Tháng hiện tại:</label>
                            <p id="add_month">{{ now()->format('m/Y') }}</p>
                        </div>
                    </div>
                    <div class="row">
                            <div class="form-group col-sm-12 col-md-6">
                            <label class="font-weight-bold text-primary">Đơn giá điện (VNĐ/kWh):</label>
                            <p id="add_electricity_rate"></p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            <label class="font-weight-bold text-primary">Đơn giá nước (VNĐ/m³):</label>
                            <p id="add_water_rate"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-6">
                            <label for="electricity_reading" class="font-weight-bold text-primary">Chỉ số điện (kWh):</label>
                            <input type="number" name="electricity_reading" id="electricity_reading" class="form-control" min="0" step="1" value="{{ old('electricity_reading') }}">
                            <span class="text-danger" id="add_electricity_reading-error"></span>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            <label for="water_reading" class="font-weight-bold text-primary">Chỉ số nước (m³):</label>
                            <input type="number" name="water_reading" id="water_reading" class="form-control" min="0" step="1" value="{{ old('water_reading') }}">
                            <span class="text-danger" id="add_water_reading-error"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary" id="addUtilityBtn">Thêm tiện ích</button>
                </div>
            </form>
        </div>
    </div>
</div>