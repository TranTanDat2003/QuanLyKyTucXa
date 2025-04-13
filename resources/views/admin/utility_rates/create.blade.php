<div class="modal fade" id="addUtilityRateModal" tabindex="-1" role="dialog" aria-labelledby="addUtilityRateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUtilityRateModalLabel">Thêm giá tiện ích mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="POST" action="{{ route('utility_rates.store') }}" id="addUtilityRateForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="electricity_rate">Giá điện (VNĐ)</label>
                        <input type="number" name="electricity_rate" id="electricity_rate" class="form-control" min="1000" step="100" value="{{ old('electricity_rate') }}">
                        <span class="text-danger" id="add_electricity_rate-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="water_rate">Giá nước (VNĐ)</label>
                        <input type="number" name="water_rate" id="water_rate" class="form-control" min="1000" step="100" value="{{ old('water_rate') }}">
                        <span class="text-danger" id="add_water_rate-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="effective_date">Ngày hiệu lực</label>
                        <input type="date" name="effective_date" id="effective_date" class="form-control" value="{{ old('effective_date') }}">
                        <span class="text-danger" id="add_effective_date-error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary" id="addUtilityRateBtn">Thêm giá tiện ích</button>
                </div>
            </form>
        </div>
    </div>
</div>