<div class="modal fade" id="editSemesterModal" tabindex="-1" role="dialog" aria-labelledby="editSemesterModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSemesterModalLabel">Chỉnh sửa học kỳ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="POST" id="editSemesterForm">
                @method('PUT')
                @csrf
                <input type="hidden" name="semester_id" id="edit_semester_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_semester_name">Tên học kỳ</label>
                        <input type="text" name="semester_name" id="edit_semester_name" class="form-control" value="{{ old('semester_name') }}">
                        <span class="text-danger" id="edit_semester_name-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_academic_year">Năm học</label>
                        <input type="text" name="academic_year" id="edit_academic_year" class="form-control" value="{{ old('academic_year') }}">
                        <span class="text-danger" id="edit_academic_year-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_start_date">Ngày bắt đầu</label>
                        <input type="date" name="start_date" id="edit_start_date" class="form-control" value="{{ old('start_date') }}">
                        <span class="text-danger" id="edit_start_date-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_end_date">Ngày kết thúc</label>
                        <input type="date" name="end_date" id="edit_end_date" class="form-control" value="{{ old('end_date') }}">
                        <span class="text-danger" id="edit_end_date-error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary" id="editSemesterBtn">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>