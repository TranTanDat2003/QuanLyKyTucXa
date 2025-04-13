<!-- Modal Xem Chi Tiết Loại Phòng -->
<div class="modal fade" id="viewRoomTypeModal" tabindex="-1" role="dialog" aria-labelledby="viewRoomTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewRoomTypeModalLabel">Chi tiết loại phòng</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Thông tin chi tiết của RoomType -->
                <div class="row">
                    <div class="col-md-4">
                        <img id="view_room_type_img" class="img-fluid" src="{{ asset('images/profiles/default_profile.jpg') }}" alt="Hình ảnh loại phòng">
                    </div>
                    <div class="col-md-8">
                        <p><strong>Tên loại phòng:</strong> <span id="view_room_type_name"></span></p>
                        <p><strong>Sức chứa:</strong> <span id="view_capacity"></span></p>
                        <p><strong>Giá (VNĐ):</strong> <span id="view_room_type_price"></span></p>
                        <p><strong>Có máy lạnh:</strong> <span id="view_has_air_conditioner"></span></p>
                        <p><strong>Cho phép nấu ăn:</strong> <span id="view_allow_cooking"></span></p>
                    </div>
                </div>

                <!-- Bảng danh sách các phòng -->
                <div class="card">
                    <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Danh sách phòng</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="roomsTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Mã phòng</th>
                                        <th>Số chỗ trống</th>
                                        <th>Trạng thái</th>
                                        <th>Giới tính</th>
                                    </tr>
                                </thead>
                                <tbody id="roomsList">
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