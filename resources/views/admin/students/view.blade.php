<div class="modal fade" id="viewStudentModal" tabindex="-1" role="dialog" aria-labelledby="viewStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewStudentModalLabel">Chi tiết sinh viên</h5>
                <button type="button" class="close" data-dismiss="modal" aria="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <img id="view_image" class="img-fluid" src="{{ asset('images/profiles/default_profile.jpg') }}" alt="Ảnh sinh viên">
                    </div>
                    <div class="col-md-8">
                        <p><strong>Mã sinh viên:</strong> <span id="view_student_code"></span></p>
                        <p><strong>Họ tên:</strong> <span id="view_full_name"></span></p>
                        <p><strong>Ngày sinh:</strong> <span id="view_date_of_birth"></span></p>
                        <p><strong>Giới tính:</strong> <span id="view_gender"></span></p>
                        <p><strong>Số điện thoại:</strong> <span id="view_phone"></span></p>
                        <p><strong>Địa chỉ:</strong> <span id="view_address"></span></p>
                        <p><strong>Email:</strong> <span id="view_email"></span></p>
                        <p><strong>Ngành học:</strong> <span id="view_major"></span></p>
                        <p><strong>Lớp:</strong> <span id="view_class"></span></p>
                        <p><strong>Trạng thái tài khoản:</strong> <span id="view_status"></span></p>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Danh sách hóa đơn dịch vụ</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="serviceBillsTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Mã hóa đơn</th>
                                        <th>Tổng tiền</th>
                                        <th>Đã thanh toán</th>
                                        <th>Ngày phát hành</th>
                                        <th>Ngày đến hạn</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody id="serviceBillsList">
                                    <!-- Dữ liệu sẽ được tải bằng AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal hiển thị chi tiết service_bill_items -->
<div class="modal fade" id="viewServiceBillItemsModal" tabindex="-1" role="dialog" aria-labelledby="viewServiceBillItemsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewServiceBillItemsModalLabel">Chi tiết hóa đơn dịch vụ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="serviceBillItemsTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Tên dịch vụ</th>
                                <th>Giá dịch vụ</th>
                                <th>Tổng tiền</th>
                                <th>Ngày bắt đầu</th>
                                <th>Ngày kết thúc</th>
                            </tr>
                        </thead>
                        <tbody id="serviceBillItemsList">
                            <!-- Dữ liệu sẽ được tải bằng AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>