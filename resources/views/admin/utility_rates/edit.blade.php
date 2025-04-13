<div class="modal fade" id="editUtilityRateModal" tabindex="-1" role="dialog" aria-labelledby="editUtilityRateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUtilityRateModalLabel">Chỉnh sửa giá tiện ích</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="POST" id="editUtilityRateForm">
                @method('PUT')
                @csrf
                <input type="hidden" name="rate_id" id="edit_rate_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_electricity_rate">Giá điện (VNĐ)</label>
                        <input type="number" name="electricity_rate" id="edit_electricity_rate" class="form-control" min="1000" step="100" value="{{ old('electricity_rate') }}">
                        <span class="text-danger" id="edit_electricity_rate-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_water_rate">Giá nước (VNĐ)</label>
                        <input type="number" name="water_rate" id="edit_water_rate" class="form-control" min="1000" step="100" value="{{ old('water_rate') }}">
                        <span class="text-danger" id="edit_water_rate-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_effective_date">Ngày hiệu lực</label>
                        <input type="date" name="effective_date" id="edit_effective_date" class="form-control" value="{{ old('effective_date') }}">
                        <span class="text-danger" id="edit_effective_date-error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary" id="editUtilityRateBtn">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>