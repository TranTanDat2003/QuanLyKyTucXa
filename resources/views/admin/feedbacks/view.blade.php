<div class="modal fade" id="viewFeedbackModal" tabindex="-1" role="dialog" aria-labelledby="viewFeedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewFeedbackModalLabel">Chi tiết báo cáo lỗi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <img id="view_image" class="img-fluid" src="{{ asset('images/profiles/default_profile.jpg') }}" alt="Ảnh minh họa">
                    </div>
                    <div class="col-md-8">
                        <p><strong>Mã phòng:</strong> <span id="view_room_code"></span></p>
                        <p><strong>Sinh viên:</strong> <span id="view_student_name"></span></p>
                        <p><strong>Nội dung:</strong> <span id="view_content"></span></p>
                        <p><strong>Số lượng lỗi:</strong> <span id="view_quantity"></span></p>
                        <p><strong>Trạng thái:</strong> <span id="view_status"></span></p>
                        <p><strong>Ngày gửi:</strong> <span id="view_created_at"></span></p>
                        <p><strong>Ngày hẹn sửa:</strong> <span id="view_scheduled_fix_date"></span></p>
                        <p><strong>Nhân viên duyệt:</strong> <span id="view_staff_name"></span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>