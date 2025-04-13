<!-- Modal Xem Chi Tiết Hợp Đồng -->
<div class="modal fade" id="viewContractModal" tabindex="-1" role="dialog" aria-labelledby="viewContractModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewContractModalLabel">Chi tiết hợp đồng</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Mã sinh viên:</strong> <span id="view_student_code"></span></p>
                        <p><strong>Tên sinh viên:</strong> <span id="view_student_name"></span></p>
                        <p><strong>Mã phòng:</strong> <span id="view_room_code"></span></p>
                        <p><strong>Học kỳ:</strong> <span id="view_semester_name"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Trạng thái:</strong> <span id="view_status"></span></p>
                        <p><strong>Ngày bắt đầu:</strong> <span id="view_contract_start_date"></span></p>
                        <p><strong>Ngày kết thúc:</strong> <span id="view_contract_end_date"></span></p>
                        <p><strong>Tổng chi phí:</strong> <span id="view_contract_cost"></span></p>
                        <p><strong>Đã thanh toán:</strong> <span id="view_paid_amount"></span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>