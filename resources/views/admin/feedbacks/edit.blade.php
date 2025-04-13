<!-- Modal nhập ngày hẹn sửa chữa -->
<div class="modal fade" id="approveDateModal" tabindex="-1" role="dialog" aria-labelledby="approveDateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveDateModalLabel">Nhập ngày hẹn sửa chữa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="approve_scheduled_fix_date">Ngày hẹn sửa chữa</label>
                    <input type="date" id="approve_scheduled_fix_date" class="form-control" required>
                    <span class="text-danger" id="approve_scheduled_fix_date-error"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="submitApproveDateBtn">Xác nhận</button>
            </div>
        </div>
    </div>
</div>