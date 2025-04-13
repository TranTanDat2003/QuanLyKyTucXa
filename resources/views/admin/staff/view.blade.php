<div class="modal fade" id="viewStaffModal" tabindex="-1" role="dialog" aria-labelledby="viewStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewStaffModalLabel">Chi tiết nhân viên</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <img id="view_image" class="img-fluid" src="{{ asset('images/profiles/default_profile.jpg') }}" alt="Ảnh nhân viên">
                    </div>
                    <div class="col-md-8">
                        <p><strong>Mã nhân viên:</strong> <span id="view_staff_code"></span></p>
                        <p><strong>Họ tên:</strong> <span id="view_full_name"></span></p>
                        <p><strong>Ngày sinh:</strong> <span id="view_date_of_birth"></span></p>
                        <p><strong>Giới tính:</strong> <span id="view_gender"></span></p>
                        <p><strong>Số điện thoại:</strong> <span id="view_phone"></span></p>
                        <p><strong>Địa chỉ:</strong> <span id="view_address"></span></p>
                        <p><strong>Email:</strong> <span id="view_email"></span></p>
                        <p><strong>Trạng thái tài khoản:</strong> <span id="view_status"></span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>