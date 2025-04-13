<div class="modal fade" id="viewSemesterModal" tabindex="-1" role="dialog" aria-labelledby="viewSemesterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewSemesterModalLabel">Chi tiết học kỳ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p><strong>Tên học kỳ:</strong> <span id="view_semester_name"></span></p>
                        <p><strong>Năm học:</strong> <span id="view_academic_year"></span></p>
                        <p><strong>Ngày bắt đầu:</strong> <span id="view_start_date"></span></p>
                        <p><strong>Ngày kết thúc:</strong> <span id="view_end_date"></span></p>
                        <p><strong>Trạng thái:</strong> <span id="view_status"></span></p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Danh sách hợp đồng</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="contractsTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Mã hợp đồng</th>
                                        <th>Mã sinh viên</th>
                                        <th>Mã phòng</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody id="contractsList">
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