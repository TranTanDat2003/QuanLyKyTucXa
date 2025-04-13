<div class="modal fade" id="viewUtilityModal" tabindex="-1" role="dialog" aria-labelledby="viewUtilityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewUtilityModalLabel">Chi tiết tiện ích phòng <span id="view_room_code"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-sm-12 col-md-4">
                        <label  class="font-weight-bold text-primary">Mã phòng:</label>
                        <p id="view_room_code_display"></p>
                    </div>
                    <div class="form-group col-sm-12 col-md-4">
                        <label class="font-weight-bold text-primary">Đơn giá điện (VNĐ/kWh):</label>
                        <p id="view_electricity_rate"></p>
                    </div>
                    <div class="form-group col-sm-12 col-md-4">
                        <label class="font-weight-bold text-primary">Đơn giá nước (VNĐ/m³):</label>
                        <p id="view_water_rate"></p>
                    </div>
                </div>

                <!-- Bảng danh sách các tiện ích -->
                <div class="card">
                    <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Danh sách tiện ích</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="utilitiesTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Tháng</th>
                                        <th>Chỉ số điện (kWh)</th>
                                        <th>Chỉ số nước (m³)</th>
                                        <th>Điện tiêu thụ (kWh)</th>
                                        <th>Nước tiêu thụ (m³)</th>
                                        <th>Tổng tiền (VNĐ)</th>
                                        <th>Người tạo</th>
                                        <th>Người thay đổi</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody id="utilitiesList">
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